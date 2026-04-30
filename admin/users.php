<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="d-flex">
        <?php include 'partials/sidebar.php'; ?>

        <div class="main-content flex-grow-1 p-4">
            <div class="page-heading">Users</div>
            <div class="records-panel">
                <div class="page active" id="page-users">
                    <div class="filter-bar">
                        <input type="text" placeholder="Search username or email" value="" class="search-input">
                        <select class="filter-select">
                            <option>All statuses</option>
                            <option>Active</option>
                            <option>Banned</option>
                            <option>Suspended</option>
                        </select>
                        <button class="btn btn-primary-solid">Filter</button>
                        <button class="btn btn-outline">Clear</button>
                    </div>
                </div>

                <table class="records-table">
                    <thead class="table-header">
                        <tr class="table-row">
                            <th style="width:28%">User</th>
                            <th style="width:24%">Email</th>
                            <th style="width:14%">Joined</th>
                            <th style="width:10%">Listings</th>
                            <th style="width:10%">Status</th>
                            <th style="width:8%">Rating</th>
                            <th style="width:6%"></th>
                        </tr>
                    </thead>

                    <tbody class="table-body">
                        <tr class="table-row clickable">
                            <td><span class="avatar">JD</span> john_doe</td>
                            <td>john@example.com</td>
                            <td>12 Jan 2025</td>
                            <td>8</td>
                            <td><span class="badge badge-green">Active</span></td>
                            <td>4.5</td>
                            <td><a href="user-details.php?id=<?= 1 ?>" class="btn btn-primary-solid view-btn">View</a></td>
                        </tr>

                        <tr class="table-row clickable">
                            <td><span class="avatar" style="background:#FCEBEB;color:#A32D2D">SM</span> sarah_m</td>
                            <td>sarah@mail.com</td>
                            <td>3 Feb 2025</td>
                            <td>14</td>
                            <td><span class="badge badge-red">Banned</span></td>
                            <td>3.2</td>
                            <td><a href="user-details.php?id=<?= 1 ?>" class="btn btn-primary-solid view-btn">View</a></td>
                        </tr>

                        <tr class="table-row clickable">
                            <td><span class="avatar" style="background:#FAEEDA;color:#854F0B">TP</span> the_pete</td>
                            <td>pete@hey.com</td>
                            <td>19 Mar 2025</td>
                            <td>3</td>
                            <td><span class="badge badge-amber">Suspended</span></td>
                            <td>2.8</td>
                            <td><a href="user-details.php?id=<?= 1 ?>" class="btn btn-primary-solid view-btn">View</a></td>
                        </tr>

                        <tr class="table-row clickable">
                            <td><span class="avatar">LK</span> lk_trades</td>
                            <td>lk@trades.co</td>
                            <td>1 Apr 2025</td>
                            <td>21</td>
                            <td><span class="badge badge-green">Active</span></td>
                            <td>4.9</td>
                            <td><a href="user-details.php?id=<?= 1 ?>" class="btn btn-primary-solid view-btn">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>