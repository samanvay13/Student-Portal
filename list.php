<?php
require_once('config.php');
session_start();

if (isset($_SESSION['User'])) {
    $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
	$sortOrder = '';

	if (strpos($sortBy, 'ASC') !== false) {
		$sortBy = str_replace(' ASC', '', $sortBy);
		$sortOrder = 'ASC';
	} elseif (strpos($sortBy, 'DESC') !== false) {
		$sortBy = str_replace(' DESC', '', $sortBy);
		$sortOrder = 'DESC';
	}
    $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

    if (!empty($searchKeyword)) {
        $searchSql = "SELECT * FROM students WHERE name LIKE :keyword OR email LIKE :keyword OR gender LIKE :keyword ORDER BY $sortBy";
        $stmt = $db->prepare($searchSql);
        $stmt->bindValue(':keyword', '%' . $searchKeyword . '%');
    } else {
        $sql = "SELECT * FROM students ORDER BY $sortBy $sortOrder";
        $stmt = $db->prepare($sql);
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rowCount = count($rows);
} else {
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>CRUD</title>
    <link href="style.css" rel="stylesheet">
	<script>
		function confirmDelete(){
			var result = confirm("Are you sure you want to delete?");
			if(result){
				<?php
					if (isset($_GET['delete_id'])) {
						$deleteId = $_GET['delete_id'];
						$deleteSql = "DELETE FROM students WHERE id = :id";
						$deleteStmt = $db->prepare($deleteSql);
						$deleteStmt->bindParam(':id', $deleteId);
						$deleteStmt->execute();
						header("Location: list.php");
						exit;
					}
				?>
			} else {
				event.preventDefault();
			}
		}
		function confirmLogout(){
			var result = confirm("Are you sure you want to Logout?");
			if(result){
				<?php
					if (isset($_GET['logout'])) {
						session_destroy();
						header("location:login.php");
						exit;
					}
				?>
			} else {
				event.preventDefault();
			}
		}
		function toggleSort(column) {
			var currentUrl = window.location.href;
			var sortParam = 'sort=' + column;

			if (currentUrl.includes(sortParam)) {
				if (currentUrl.includes('ASC')) {
					sortParam += ' DESC';
				} else {
					sortParam += ' ASC';
				}
				currentUrl = currentUrl.replace(/sort=[^&]*/, sortParam);
			} else {
				if (currentUrl.includes('?')) {
					currentUrl += '&' + sortParam + ' ASC';
				} else {
					currentUrl += '?' + sortParam + ' ASC';
				}
			}

			window.location.href = currentUrl;
		}
	</script>
	</head>
	<body>
		<div class="hotel-listing">
			<div class="regi-main">
				<div id="wrapper2">
					<div class="my-account">
						<div class="myaccount-outer ">
							<div class="myaccount-outer">
								<div class="account-right-div">
									<div class="dashboard-heading">
										<h2>Manage Blogs</h2>
									</div>
									<div class="dashboard-inner">
										<div class="dash-search">
											<form method="get" id="frmSearchUser" name="frmSearchUser" action="list.php?sort=<?php echo $sortBy; ?>"&search=<?php echo $searchKeyword; ?>>
												<input type="text" placeholder="Keyword" class="list-search" id="searchUserEmail" name="search" value="<?php echo $searchKeyword; ?>">
												<input type="submit" name="serch_btn" id="serch_btn" value="Search" class="add-user search-icon crome-left">
												<span class="reg_span">
													<div class="user-btn-div plus">
														<a title="Add Student" href="http://localhost/student%20registration/registration.php"></i>Add New Record</a>
													</div>
												</span>
											</form>
										</div>
										<div class="total_rec">
											<div class="block-used1"> <span>Total Records: </span>
												<?php
												echo $rowCount;
												?>
											</div>
										</div>
										<div class="main-dash-summry Edit-profile nopadding11">
											<!--table-->
											<div class="my_table_div">
												<table class="fixes_layout">
													<thead>
														<tr>
															<th class="forWidthSno" width="10%"><h1 class="">S. No.</h1></th>
															<th width="20%"><a class="underline_classs" href="list.php?sort=name"><h1 class="sort" onclick="toggleSort('name')">Name</h1></a></th>
															<th width="25%"><a class="underline_classs" href="list.php?sort=email"><h1 class="sort" onclick="toggleSort('email')">Email</h1></a></th>
															<th width="15%"><a class="underline_classs" href="list.php?sort=id"><h1 class="sort" onclick="toggleSort('id')">Reg No</h1></a></th>
															<th width="20%"><a class="underline_classs" href="list.php?sort=gender"><h1 class="sort" onclick="toggleSort('gender')">Gender</h1></a></th>
															<th width="10%"><a class="underline_classs" href=""><h1 class="sort">Actions</h1></a></th>
														</tr>
													</thead>
													<tbody>
													<?php
													$sn = 1;
													foreach ($rows as $row) {
														?>
														<tr>
															<td><?php echo $sn; ?></td>
															<td><?php echo $row['name']; ?></td>
															<td><?php echo $row['email']; ?></td>
															<td><?php echo $row['id']; ?></td>
															<td><?php echo $row['gender']; ?></td>
															<td class="action-main-block">
																<a class="edit"
																href="http://localhost/student%20registration/registration.php?edit_id=<?php echo $row['id']; ?>"
																title="Edit Blog">&nbsp;
																</a>
																<a class="del del-dealer"
																href="http://localhost/student%20registration/list.php?delete_id=<?php echo $row['id']; ?>"
																onclick="confirmDelete()"
																data-id="140" title="Delete Blog">&nbsp;
																</a>
															</td>
															<tr>
														<?php $sn++;
													} ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="user-btn-div plus">
							<a title="Logout" href="list.php?logout" onclick="confirmLogout()"></i>Logout</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
