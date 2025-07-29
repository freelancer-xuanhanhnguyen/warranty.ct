<style>

    @font-face {
        font-family: 'Gilroy';
        font-style: normal;
        font-weight: 100;
        src: url('../fonts/SVN-Gilroy Thin.otf') format('opentype');
    }

    @font-face {
        font-family: 'Gilroy';
        font-style: italic;
        font-weight: 100;
        src: url('../fonts/SVN-Gilroy Thin Italic.otf') format('opentype');
    }

    @font-face {
        font-family: 'Gilroy';
        font-style: normal;
        font-weight: 300;
        src: url('../fonts/SVN-Gilroy Light.otf') format('opentype');
    }

    @font-face {
        font-family: 'Gilroy';
        font-style: italic;
        font-weight: 300;
        src: url('../fonts/SVN-Gilroy Light Italic.otf') format('opentype');
    }

    /* ... tương tự với Medium(500), Regular(400), SemiBold(600), Bold(700), XBold(800), Heavy(900) ... */
    @font-face {
        font-family: 'Gilroy';
        font-style: normal;
        font-weight: 400;
        src: url('../fonts/SVN-Gilroy Regular.otf') format('opentype');
    }

    @font-face {
        font-family: 'Gilroy';
        font-style: normal;
        font-weight: 700;
        src: url('../fonts/SVN-Gilroy Bold.otf') format('opentype');
    }

    :root {
        --bs-body-font-family: 'Gilroy', sans-serif;
    }

    #page-container.sidebar-dark #sidebar,
    #page-container.sidebar-dark #sidebar .content-header,
    #page-container.page-header-dark #page-header {
        background-color: #123ac2;
    }

    .form-control {
        font-family: 'Gilroy', sans-serif;
    }

    .text-line-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* number of lines to show */
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .text-line-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3; /* number of lines to show */
        line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .invalid-feedback::first-letter {
        text-transform: uppercase !important;
    }

    th.sortable {
        cursor: pointer;
        white-space: nowrap;
    }

    th.sortable::after {
        content: ' ⇅';
        font-size: 0.8em;
        color: #888;
    }

    th.sortable.--asc::after {
        content: ' ↑';
    }

    th.sortable.--desc::after {
        content: ' ↓';
    }

</style>
