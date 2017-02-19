<!doctype html>
<html>
    <head>
        <title>Search with Algolia</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/app.css">
        <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
        <script src="./app.js"></script>
        <!-- TODO: add polyfill for fetch -->
    </head>

    <body>
        <form>
            <input id="searchInput" type="text" value="" placeholder="Search for an app..." autocomplete="off">
            <button id="addButton" type="button" value="add">New</button>

            <section id="results">
            </section>
        </form>

        <script>
            initApp({
                algolia_applicationID: '<?php echo $templateParams['algolia_applicationID'] ?>',
                algolia_apiKey: '<?php echo $templateParams['algolia_apiKey'] ?>',
                algolia_indexName: '<?php echo $templateParams['algolia_indexName'] ?>',
            });
        </script>
    </body>
</html>
