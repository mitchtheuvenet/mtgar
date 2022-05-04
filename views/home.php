<?php 

$this->title = 'Home';

?>

<div class="col-md-4 offset-md-4 text-center">
    <h1>Home</h1>
    <p class="lead">Welcome, <?= $name ?>!</p>
    <a href="/" tabindex="-1">
        <img src="images/logo.png" class="img-fluid" alt="Logo">
    </a>
    <div class="my-4">
        <a class="twitter-timeline" data-width="1024" data-height="384" data-dnt="true" href="https://twitter.com/wizards_magic?ref_src=twsrc%5Etfw">Tweets by wizards_magic</a>
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    </div>
</div>