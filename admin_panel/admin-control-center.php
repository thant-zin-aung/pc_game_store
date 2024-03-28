<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');
    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }
    $totalNewOrder = get_total_new_orders_count($connect);
    if ( isset($_POST['create-button']) ) {
        $adminRole = $_POST['admin-role-name'];
        $adminUsername = $_POST['admin-username'];
        $adminPassword = $_POST['admin-password'];
        $addNewAdminPermission = isset($_POST['add-new-admin-permission']) ? "yes" : "no";
        $editDeletePermission = isset($_POST['edit-delete-permission']) ? "yes" : "no";
        $orderApprovalPermission = isset($_POST['order-approval-permission']) ? "yes" : "no";
        $imageUploaderPermission = isset($_POST['image-uploader-permission']) ? "yes" : "no";
        $guestPermission = isset($_POST['guest-permission']) ? "yes" : "no";
        if ( ( $addNewAdminPermission === "yes" || $editDeletePermission === "yes" || $orderApprovalPermission === "yes" || $imageUploaderPermission === "yes") || $guestPermission === "yes" ) {
            if ( !check_if_admin_already_exists($connect,$adminUsername) ) {
                $addAdminQuery = "INSERT INTO admin_list(admin_username,admin_password,admin_role,permission_add_new_admin,permission_delete_game,permission_order_approval,permission_image_uploader) VALUES(
                    '$adminUsername','$adminPassword','$adminRole','$addNewAdminPermission','$editDeletePermission','$orderApprovalPermission','$imageUploaderPermission'            
                )";
                $addAdminQueryResult = mysqli_query($connect,$addAdminQuery);
                if ( $addAdminQueryResult ) {
                    echo "<script>alert('New admin added successfully...');</script>";
                } else {
                    echo "<script>alert('Warning!!! Failed to add new admin...');</script>";
                }
            } else {
                echo "<script>alert('Warning!!! Admin username \"$adminUsername\" is already exists...');</script>";   
            }
        } else {
            echo "<script>alert('Warning!!! Please select at least one permission...');</script>";
        }        
    }

    $adminListQuery = "SELECT * FROM admin_list";
    $adminListQueryResult = mysqli_query($connect,$adminListQuery);
    $totalAdmin = mysqli_num_rows($adminListQueryResult);
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blacksky PC Game Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Megrim&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e2c9faac31.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/app-style.css">
    <!-- <link rel="stylesheet" href="styles/order-style.css"> -->
    <!-- <link rel="stylesheet" href="styles/game-list-style.css"> -->
    <link rel="stylesheet" href="styles/admin-control-center.css">
</head>
<body>
    <nav>
        <div class="page-logo-wrapper">
            <div class="left">
                <i class="fa-solid fa-gamepad"></i>
            </div>
            <div class="right">
                BLACKSKY <br><span>pc game store...</span>
            </div>
        </div>
        <div class="login-user"><i class="fa-solid fa-unlock-keyhole"></i> Logged in as <u>blacksky</u> <br> <span>Admin</span></div>
        <div class="tab-wrapper">
            <div class="dashboard-tab tab"><span><i class="fa-solid fa-border-all"></i> Dashboard</span></div>
            <div class="order-tab tab"><span><i class="fa-solid fa-money-bills"></i> Orders</span> <div class="new-order-number" style="display: <?php echo $totalNewOrder== 0 ? "none" : "flex"?>;"><?php echo $totalNewOrder;?></div></div>
            <div class="customer-tab tab"><span><i class="fa-solid fa-users"></i> Customers</span></div>
            <div class="game-list-tab tab"><span><i class="fa-solid fa-list"></i> Game list</span></div>
            <div class="admin-control-tab tab active-tab"><span><i class="fa-solid fa-user-tie"></i> Admin Control</span></div>
            <div class="image-uploader-tab tab"><span><i class="fa-regular fa-file-image"></i> Image Uploader</span></div>
            <div class="log-out-tab tab"><span><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</span></div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="title">Admin Control Center</div>
        
        <div class="control-center-wrapper">
            <div class="control-center-title">Create New Admin Account</div>
            <form action="admin-control-center.php" method="POST">
                <div class="input-group-wrapper">
                    <div class="input-group">
                        <div class="label">Role name</div>
                        <input type="text" spellcheck="false" placeholder="Enter role name..." name="admin-role-name" required>
                    </div>
                    <div class="input-group">
                        <div class="label">Username</div>
                        <input type="text" spellcheck="false" placeholder="Enter username..." name="admin-username" required>
                    </div>
                    <div class="input-group">
                        <div class="label">Password</div>
                        <input type="text" spellcheck="false" placeholder="Enter password..." name="admin-password" required>
                    </div>
                    <div class="input-group">
                        <div class="label">Permissions</div>
                        <!-- <input type="text" spellcheck="false" placeholder="Enter password..."> -->
                        <div class="permission-group">
                            <!-- <input type="checkbox" id="create-delete-admin-account">
                            <label for="create-delete-admin-account">Create/Delete Admin Account</label> -->
                            <input type="checkbox" id="add-new-admin" class="permission-checkbox add-new-admin-permission-checkbox" name="add-new-admin-permission">
                            <label for="add-new-admin"><strong>Add new admin</strong></label>

                            <input type="checkbox" id="edit-delete-game" class="permission-checkbox" name="edit-delete-permission">
                            <label for="edit-delete-game"><strong>Edit</strong> / <strong>Delete</strong> Games</label>

                            <input type="checkbox" id="order-approval" class="permission-checkbox" name="order-approval-permission">
                            <label for="order-approval"><strong>Order</strong> Approval</label>

                            <input type="checkbox" id="access-image-uploader" class="permission-checkbox" name="image-uploader-permission">
                            <label for="access-image-uploader">Access <strong>Image Uploader</strong></label>

                            <input type="checkbox" id="guest-permission" class="permission-checkbox guest-permission-checkbox" name="guest-permission">
                            <label for="guest-permission"><strong>Guest</strong> Permission</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="create-button" name="create-button"><i class="fa-solid fa-circle-plus"></i> CREATE</button>
            </form>

            <div class="admin-list-and-permission-wrapper">
                <div class="admin-list-title"><i class="fa-solid fa-list-check"></i> &nbsp; Admin List And Permission Table</div>
                <table class="admin-list-and-permission-table" cellspacing="0">
                    <tr>
                        <th># Admin ID</th>
                        <th>Admin Role</th>
                        <th>Admin Username</th>
                        <th class="permission">Read Access</th>
                        <th class="permission">Add New Admin</th>
                        <th class="permission">Order Approval</th>
                        <th class="permission">Delete Games</th>
                        <th class="permission">Image Uploader</th>
                        <th class="action-column">Action</th>
                    </tr>
                    <?php
                        for ( $count = 0 ; $count < $totalAdmin ; $count++ ) {
                            $adminData = mysqli_fetch_array($adminListQueryResult);
                            $adminId = $adminData['id'];
                            $adminUsername = $adminData['admin_username'];
                            $adminRole = $adminData['admin_role'];
                            $addNewAdminPermission = $adminData['permission_add_new_admin'];
                            $editDeletePermission = $adminData['permission_delete_game'];
                            $orderApprovalPermission = $adminData['permission_order_approval'];
                            $imageUploaderPermission = $adminData['permission_image_uploader'];
                            ?>
                            <tr>
                                <td><?php echo $adminId;?></td>
                                <td><?php echo $adminRole;?></td>
                                <td><?php echo $adminUsername;?></td>
                                <td class="permission allowed-permission"><i class="fa-regular fa-circle-check"></i></td>
                                <td class="permission <?php echo ($addNewAdminPermission==='yes')?'allowed-permission':'disallowed-permission';?>"><i class="fa-regular <?php echo ($addNewAdminPermission==='yes')?'fa-circle-check':'fa-circle-xmark';?>"></i></td>
                                <td class="permission <?php echo ($editDeletePermission==='yes')?'allowed-permission':'disallowed-permission';?>"><i class="fa-regular <?php echo ($editDeletePermission==='yes')?'fa-circle-check':'fa-circle-xmark';?>"></i></td>
                                <td class="permission <?php echo ($orderApprovalPermission==='yes')?'allowed-permission':'disallowed-permission';?>"><i class="fa-regular <?php echo ($orderApprovalPermission==='yes')?'fa-circle-check':'fa-circle-xmark';?>"></i></td>
                                <td class="permission <?php echo ($imageUploaderPermission==='yes')?'allowed-permission':'disallowed-permission';?>"><i class="fa-regular <?php echo ($imageUploaderPermission==='yes')?'fa-circle-check':'fa-circle-xmark';?>"></i></td>
                                <td class="action-column"><div class="delete-admin-button"><i class="fa-regular fa-trash-can"></i> DELETE</div></td>
                            </tr>
                            <?php
                        }
                    ?>
                </table>
            </div>


            <div class="admin-login-action-title-wrapper">
                <div class="title"><i class="fa-solid fa-clock-rotate-left"></i> &nbsp; Admin Login History And Actions</div>
                <!-- Filter section code is not implemented yet... -->
                <div class="filter-wrapper"><i class="fa-solid fa-filter"></i> Filter</div>
            </div>
            <table class="admin-login-action-table" cellspacing="0">
                <tr>
                    <th># Admin ID</th>
                    <th>Admin Role</th>
                    <th>Admin Username</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                    <th>Events</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Admin</td>
                    <td>blacksky00797</td>
                    <td>22-Jan-2024</td>
                    <td>8:30 PM</td>
                    <td>delete</td>
                    <td class="event">An admin called blacksky00797(username) was logged in to admin dashboard panel.</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Admin</td>
                    <td>blacksky00797</td>
                    <td>22-Jan-2024</td>
                    <td>8:30 PM</td>
                    <td>delete</td>
                    <td class="event">An admin called blacksky00797(username) was logged in to admin dashboard panel.</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Admin</td>
                    <td>blacksky00797</td>
                    <td>22-Jan-2024</td>
                    <td>8:30 PM</td>
                    <td>delete</td>
                    <td class="event">An admin called blacksky00797(username) was logged in to admin dashboard panel.</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Admin</td>
                    <td>blacksky00797</td>
                    <td>22-Jan-2024</td>
                    <td>8:30 PM</td>
                    <td>delete</td>
                    <td class="event">An admin called blacksky00797(username) was logged in to admin dashboard panel.</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Admin</td>
                    <td>blacksky00797</td>
                    <td>22-Jan-2024</td>
                    <td>8:30 PM</td>
                    <td>delete</td>
                    <td class="event">An admin called blacksky00797(username) was logged in to admin dashboard panel.</td>
                </tr>

            </table>
        </div>

        <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
        </div>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <script src="scripts/admin-control-center.js"></script>
    <script src="scripts/tab-navigator.js"></script>
    
</body>
</html>