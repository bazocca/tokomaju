<?php $this->Get->create($data); ?>
<html>
	<head>
		<title>Front End Web</title>
	</head>
	<body>
		<?php
			$pass['open_tag'] = '<li>';
			$pass['close_tag'] = '</li>';
			$pass['language'] = 'id';
			dpr($this->Get->navigation($pass));
			dpr($data); 
		?>
	</body>
</html>