<?php
$dbParams = require (
	'db.php'
);

$db=new PDO(
	"mysql: host=localhost; dbname=".
	$dbParams['database'].
	"; charset=utf8",
	$dbParams['username'],
	$dbParams['password']
); 
$groupsSql = '
	SELECT `student`.`lastName`,`student`.`firstName`,`student`.`patronymicName`,`group`.`number` FROM `student`
	INNER JOIN `group` ON `group`.`id` = `student`.`groupId`
';

$values=[];
if(isset($_GET['search'])) {
	$groupsSql.='WHERE `lastName` LIKE :value 
	OR `firstName` LIKE :value 
	OR `patronymicName` LIKE :value
	ORDER BY `number`,`lastName`';
	$values['value']='%'.$_GET['search'].'%';
}
$groupsQuery= $db
	-> prepare($groupsSql);
$groupsQuery
	-> execute($values);
$groups = $groupsQuery
	-> fetchAll (PDO :: FETCH_ASSOC);
?>
<html>
	<body>
		<form>
		<input type="text" name="search" value="<?php 
		if(isset($_GET['search'])){
				echo htmlspecialchars($_GET['search']);
			}
		?>">
		<input type="submit" value="Найти">
		<a href="index.php">Все записи</a>
		</form>
		<table border=1 cellspacing=0>
			<tr>
				<th>ФИО</th>
				<th>Номер группы</th>
			</tr>
		<?php
			foreach ($groups as $group) {
				?>
				<tr>
					<td>
				<?php
				echo htmlspecialchars($group ['lastName'].' '.$group ['firstName'].' '.$group ['patronymicName']);
				?>
					</td>
					<td>
				<?php
				echo htmlspecialchars ($group ['number']);
				?>
					</td>
				</tr>
				<?php
			}
		?>
		</table>
	</body>
</html>

	