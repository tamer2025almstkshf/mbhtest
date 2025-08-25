<?php

/**
 * Renders a view file and extracts data for it.
 *
 * This function provides a clean way to separate PHP logic from HTML presentation.
 * It includes the view file within a function scope, preventing the view from
 * accessing variables from the global scope, except for the ones passed
- * explicitly in the $data array.
 *
 * @param string $path The path to the view file (e.g., 'clients.view.php').
 * @param array $data An associative array of data to be made available to the view.
 *                    The keys of the array become variable names in the view.
 */
function render(string $path, array $data = [])
{
    // The extract() function imports variables from an array into the current symbol table.
    // This is how we make the data available to the view file.
    // For example, an array ['clients' => $clients_array] will create a $clients variable
    // inside the view file.
    extract($data);

    // Using output buffering to capture the included file's content.
    // This is a robust way to ensure that the view is rendered cleanly
    // and no accidental output interferes with headers or other logic.
    ob_start();

    // Include the view file. Because this is inside a function, the view has its
    // own isolated scope.
    require __DIR__ . '/../views/' . $path;

    // Get the captured content from the buffer and clean the buffer.
    $content = ob_get_clean();

    // Return the rendered content. This can be echoed or included in a larger layout.
    echo $content;
}
