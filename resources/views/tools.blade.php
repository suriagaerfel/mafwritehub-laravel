
<?php require resource_path('processing/content-handlers.php'); ?>

<?php $pageName = $contentTitle ? $contentTitle : 'Tools'?>


<x-main>
    @include('components/head')
    @include('components/header')


<body>
    @include('components/content')
    @include('components/footer')
</body>
</x-main>