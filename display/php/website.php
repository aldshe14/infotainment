<?php
        $url="http://htl-shkoder.com/wp-json/wp/v2/posts";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
        $posts = json_decode($result, true);
        foreach ($posts as $post) { ?>
            <a href="<?php echo $post['link']; ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?php echo $post['title']['rendered']; ?></h5>
                <small><?php echo date('F j, Y', strtotime($post['date'])); ?></small>
                </div>
                <p class="mb-1"><?php echo $post['excerpt']['rendered']; ?></p>
                <img width="50%" src="<?php echo $post['jetpack_featured_media_url']; ?>">Test
            </a>
            <?php } ?>
