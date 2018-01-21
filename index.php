<?php
$db = new PDO(
  "mysql:host=localhost;dbname=faculty;charset=utf8", 
  "root",
  ""
);

?>
<html>
	<body>
		<form method="GET" action="index.php">
			<?php
				$subjects = $db->query('
					SELECT * FROM `subject`
				')->fetchAll();
			?>
			<select name="subject">
				<?php foreach ($subjects as $subject) { ?>
				<option
					value="<?= htmlspecialchars($subject['id']) ?>"
					<?php
						if (
							isset($_GET['subject']) &&
							$_GET['subject'] == $subject['id']
						) {
							echo ' selected';
						}
					?>
				>
					<?= htmlspecialchars($subject['name']) ?>
				</option>
				<?php } ?>
			</select>
			<input type="submit" value="Найти">
		</form>
		<?php 
		if (isset($_GET['subject'])) { 
			$query = $db->prepare('
				SELECT student.lastName FROM Student
				INNER JOIN mark on mark.studentId = student.id
				INNER JOIN course ON mark.courseId = course.id
				WHERE course.subjectId = :subject and mark.value >= 4
				ORDER BY student.lastName ASC
			');
			$query->execute(['subject' => $_GET['subject']]);
			$students = $query->fetchAll();
			if (count($students) > 0) {
			?>
			<ul>
				<?php foreach ($students as $student) { ?>
					<ul><?= htmlspecialchars($student['lastName']) ?></ul>
				<?php } ?>
			</ul>
			<?php
			} else {
				?><div>Студентов не найдено</div><?php
			}
		}
		?>
	</body>
</html>