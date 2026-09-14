<?php
class CustomFunctions {
  // prepare a get url, add a _GET without breaking existing ones
  public static function formatDynamicUrl(string $key, string $value) {
       
      $urlParts = parse_url($_SERVER['REQUEST_URI']); 
      
      //query string into an array ($params)
      $params = [];
      if (isset($urlParts['query'])) {
          parse_str($urlParts['query'], $params);
      }
  
      // Set or Overwrite the value (prevents ?id=x&id=y)
      $params[$key] = $value;
  
      // Rebuild the query string and the full path
      $newQuery = http_build_query($params);
      
      return $urlParts['path'] . '?' . $newQuery;
  }
}

?>
<nav>
    <ul class="pagination">

        <?php
        $currentPage  = max(1, (int) ($_GET['pg'] ?? 1));
        $totalPerPage = max(1, (int) ($this->_settings['user_loop_sequence'] ?? 24));
        $totalCount   = max(0, (int) ($this->data['count'] ?? 0));

        $totalPages = (int) ceil($totalCount / $totalPerPage);

        // Nothing to paginate
        if ($totalPages > 1) {

            // How many page numbers to show around the current page
            $window = 2;

            /*
             * Build the pages we want to display.
             *
             * Example with 81 pages and current page 40:
             * 1 ... 38 39 40 41 42 ... 81
             */
            $pages = [];

            // Always show first page
            $pages[] = 1;

            // Pages around current page
            $start = max(2, $currentPage - $window);
            $end   = min($totalPages - 1, $currentPage + $window);

            // Ellipsis after first page
            if ($start > 2) {
                $pages[] = '...';
            }

            for ($i = $start; $i <= $end; $i++) {
                $pages[] = $i;
            }

            // Ellipsis before last page
            if ($end < $totalPages - 1) {
                $pages[] = '...';
            }

            // Always show last page
            if ($totalPages > 1) {
                $pages[] = $totalPages;
            }


            // Previous
            if ($currentPage > 1) {
                ?>
                <li class="page-item">
                    <a class="page-link"
                       href="<?= CustomFunctions::formatDynamicUrl('pg', (string) ($currentPage - 1)) ?>">
                        Previous
                    </a>
                </li>
                <?php
            }


            // Page numbers
            foreach ($pages as $page) {

                if ($page === '...') {
                    ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    <?php
                    continue;
                }

                ?>
                <li class="page-item <?= ($currentPage == $page) ? 'active' : '' ?>">
                    <a class="page-link"
                       href="<?= CustomFunctions::formatDynamicUrl('pg', (string) $page) ?>">
                        <?= $page ?>
                    </a>
                </li>
                <?php
            }


            // Next
            if ($currentPage < $totalPages) {
                ?>
                <li class="page-item">
                    <a class="page-link"
                       href="<?= CustomFunctions::formatDynamicUrl('pg', (string) ($currentPage + 1)) ?>">
                        Next
                    </a>
                </li>
                <?php
            }

        }
        ?>

    </ul>
</nav>
