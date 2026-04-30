<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings — Lootly Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="d-md-none alert alert-warning text-center p-3 mb-0 rounded-0">
        The admin panel is optimised for desktop. Some features may not display correctly on mobile.
    </div>

    <div class="d-flex">
        <?php include 'partials/sidebar.php'; ?>

        <div class="main-content flex-grow-1 p-4">
            <div class="page-heading">Listings</div>

            <div class="records-panel">
                <!-- Filter bar -->
                <div class="filter-bar">
                    <input type="text" placeholder="Search title or seller" class="search-input">
                    <select class="filter-select">
                        <option>All categories</option>
                        <option>Audio</option>
                        <option>Electronics</option>
                        <option>Photography</option>
                    </select>
                    <button class="btn-platform btn-primary-solid">Filter</button>
                    <button class="btn-platform btn-outline">Clear</button>
                </div>

                <!-- Listings table -->
                <table class="records-table">
                    <thead>
                        <tr>
                            <th style="width:6%">ID</th>
                            <th style="width:30%">Title</th>
                            <th style="width:14%">Category</th>
                            <th style="width:16%">Seller</th>
                            <th style="width:10%">Price</th>
                            <th style="width:14%">Listed</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="clickable">
                            <td>1</td>
                            <td>Canon EOS 5D Mark IV</td>
                            <td>Photography</td>
                            <td><span class="avatar">JD</span> john_doe</td>
                            <td>R 12,500</td>
                            <td>12 Jan 2025</td>
                            <td>
                                <a href="listing-details.php?id=1" class="btn-platform btn-primary-solid view-btn">View</a>
                            </td>
                        </tr>

                        <tr class="clickable">
                            <td>2</td>
                            <td>Apple MacBook Pro 16"</td>
                            <td>Electronics</td>
                            <td><span class="avatar">SM</span> sarah_m</td>
                            <td>R 28,000</td>
                            <td>3 Feb 2025</td>
                            <td>
                                <a href="listing-details.php?id=2" class="btn-platform btn-primary-solid view-btn">View</a>
                            </td>
                        </tr>

                        <tr class="clickable">
                            <td>3</td>
                            <td>Sony WH-1000XM5 Headphones</td>
                            <td>Audio</td>
                            <td><span class="avatar">TP</span> the_pete</td>
                            <td>R 4,200</td>
                            <td>19 Mar 2025</td>
                            <td>
                                <a href="listing-details.php?id=3" class="btn-platform btn-primary-solid view-btn">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>