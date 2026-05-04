<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literasi Keuangan Transporindo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }

        .container {
            background-color: #ffffff;
            max-width: 540px;
            margin: 0 auto;
            border: 1px solid #c5c5c5;
        }

        .media-download {
            border: 1px solid #d8d8db;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-input:focus {
            outline: none;
            box-shadow: none;
        }

        .card-img-top {
            width: 100%;
            height: auto;
        }

        .card-img-header {
            position: absolute;
            max-width: 100%;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            object-fit: cover;
        }

        .image-description {
            font-size: 15px;
            color: #555;
            margin-top: 5px;
            text-align: center;
        }

        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container pt-4">
        <div class="search-container mb-3">
            <div class="input-group">
                <input type="text" id="searchInput" class="search-input form-control" placeholder="Type to search...">
                <button class="input-group-text" id="basic-addon1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="row content-loop">
            <!-- Card -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>


</body>

</html>
<script>
    let jsonData = []; // Store JSON data globally

    $(document).ready(function() {
        $('#searchInput').focus();
        // Load JSON
        $.getJSON("file.json", function(data) {
            jsonData = data; // Store data globally
            displayData(jsonData); // Display all data initially
        }).fail(function() {
            console.error("Failed to load JSON file.");
        });

        // Live search function
        $("#searchInput").on("keyup", function() {
            let searchTerm = $(this).val().toLowerCase();

            let filteredData = jsonData.filter(item => {
                // Check title and description
                let titleMatch = item.title.toLowerCase().includes(searchTerm);
                let descriptionMatch = item.description.toLowerCase().includes(searchTerm);

                // Check image descriptions
                let imageMatch = item.images.some(img => {
                    return img.description.toLowerCase().includes(searchTerm);
                });

                // Return true if any match is found
                return titleMatch || descriptionMatch || imageMatch;
            });

            // Display filtered data
            displayData(filteredData);
        });

        $('#basic-addon1').on('click', function() {
            $('#searchInput').val(''); // Clear the search input field
            displayData(jsonData); // Reset to show all data
        });
    });

    // Function to highlight search term
    function highlightSearchTerm(text, searchTerm) {
        if (!searchTerm) return text; // If no search term, return text as it is
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }

    // Function to display data
    function displayData(data) {
        let container = $(".row");
        container.empty();

        if (data.length === 0) {
            container.html("<p>No results found.</p>");
            return;
        }

        $.each(data, function(index, item) {
            // Highlight search term in title, description, and image descriptions
            let highlightedTitle = highlightSearchTerm(item.title, $('#searchInput').val());
            let cardHtml = `
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <h5 class="card-header">${highlightedTitle}</h5>
                    <div class="card-body d-flex align-items-center">
                        <div class="row">
                            ${item.images.map(img => {
                            let highlightedImageDescription = highlightSearchTerm(img.description, $('#searchInput').val());
                            return `<div class="col-12 mb-4">
                                <div class="media-download">
                                    <img
                                    loading="lazy"
                                    src="${img.url}"
                                    class="card-img-top img-fluid"
                                    alt="Image"
                                    />
                                </div>
                                <p class="image-description">${highlightedImageDescription}</p>
                             </div>`
                            }
                        ).join('')}
                        </div>
                    </div>
                    <div class="card-footer text-dark">${item.description}</div>
                </div>
            </div>
        `;

            container.append(cardHtml);
        });

        hljs.highlightAll();
    }
</script>