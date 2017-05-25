<h3><?= $data['title'] ?></h3>

<p><?= nl2br($data['content']) ?></p>
<p>Written <?= date('D, F j, Y, h:m a', strtotime($data['created'])); ?> by <?= ucfirst($_SESSION['first_name']) ?> <?= ucfirst($_SESSION['last_name']) ?> </p>