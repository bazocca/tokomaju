<?php $this->Get->create($data); ?>
<html>
	<head>
		<title>Front End Web</title>
	</head>
	<body>
		<?php
			echo $this->Get->staggingEdit(); 
			dpr($data);
		?>
	</body>
</html>