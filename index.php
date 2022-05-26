<?php

    require ('./app/database.php');
    require ('./app/facebook.php');

    function apiVideos()
    {        
        $token = $_GET['token'];
        $db = new db();
        $facebook = new Facebook($token);
        // BUSCA OS VIDEOS
        $idFacebook =isset($_GET['facebook_id']) ? $_GET['facebook_id'] : '';
        if ($idFacebook) {
            $result = $facebook->api('/'. $_GET['facebook_id']);
        } else {
            $result = $facebook->api('/me/live_videos');
        }
        //echo "<pre>". print_r($result, true) ."</pre>";
        if (isset($result['data'])) {
            foreach ($result['data'] as $video) {
                //BUSCA OS COMENTARIOS
                $comments = $facebook->api('/'.$video['id'].'/comments');
                foreach ($comments['data'] as $comment) {
                    $name = isset($comment["from"]) ? $comment["from"]["name"] : " Não identificado";
                    $createData = array(
                        'message' => $comment['message'],
                        'name' => $name,
                        'comment_id' => $comment['id'],
                        'video_id' => $video['id']
                    );
                    //echo "<pre>". print_r($createData, true) ."</pre>";
                    $db->insertComments($createData);
                    $dtComment = new DateTime($comment["created_time"]);
                    echo '
                        <div class="list-group-item list-group-item-action d-flex gap-3 py-3/"/>
                            <div class="d-flex gap-2 w-100 justify-content-between">
                                <div>
                                    <h6 class="mb-0">'. $name .'</h6>
                                    <p class="mb-0 opacity-75">'. $comment["message"] .'</p>
                                </div>
                                <small class="opacity-50 text-nowrap">'. $dtComment->format('d/m/Y H:i:s') .'</small>
                            </div>
                        </div>
                    ';
                }
            }
            
        }
    }

    function getLiveVideos ()
    {
       $db = new db();
       $resultado = $db->getComments();
       echo $resultado;
    
        
    }

    if (isset($_GET['comments'])) {
        getLiveVideos();
    }

    
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>CONNECT FACEBOOK</title>
    <!-- <https://electronjs.org/docs/tutorial/security#csp-meta-tag> -->
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline';" />
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>

    <div class="px-4 py-5 my-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form name="videos" method="GET" >
                            <div class="mb-3">
                                <label class="form-label">Token</label>
                                <input id="accesToken" name="token" type="text" class="form-control" value="EAAFjDZADfspkBAKKL4MC3Fnu2E2tLS5yJHTjZC04Ure8hFetsWKqQJgwtl3boZBywU6CWzbePN7pPH63VMarkHwsaANd5AyL2cAg2HzsZBB3HQwoZCDzjw6pWtMdSqZBpvxPuRVVXaIYlFUVKZBec8HVYQPZCS86RAtJFx6740hK13EsPxHC6GDi3SGQkasykcQq2ZCewVLpmLgZDZD" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ID FACEBOOK</label>
                                <!--<input id="facebook_id" name="facebook_id" type="text" class="form-control"
                                    placeholder="ID Página ou video" value="101036615233342" />-->
                                    <input id="facebook_id" name="facebook_id" type="text" class="form-control"
                                    placeholder="ID Página ou video" value="" />
                            </div>

                            <button class="btn btn-primary" type="submit">
                                Validar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content px-4">

        <div class="list-group w-auto">
            <?php 
             if (isset($_GET['token'])) {
                apiVideos();
            }
            ?>

        </div>

    </div>

    
    <script src="./assets/js/bootstrap.min.js"></script>
</body>
</html>