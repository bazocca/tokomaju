<?php $this->Get->create($data); ?>
<html>
	<head>
		<title>Front End Web</title>
	</head>
	<body>
		<?php
			echo $this->Get->staggingAdd(); 
			dpr($data);
		?>
	</body>
</html>