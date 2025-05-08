<?php
if (isset($_GET["type"])){
    if ($_GET["type"] === "v"){
        echo <<<EOS
            <div class="post-creation-item">
                                    <div class="delete-uploaded-item absolute">
                                        <i class="fa fa-close"></i>
                                    </div>
                                    <img  class="video-post-thumbnail" alt="" style="width: 100%;">
                                    <input type="hidden" class="pciid" value="1">
                                    <div class="post-creation-video-image-container" style="display: flex;">
                                        <i class="fad fa-circle-play"></i>
                                    </div>
                                    <div class="assets-pending" style=" display: none;">
                                        <div class="pending-container relative">
                                            <div class="pending-inner">
                                                <div class="pendulum"></div>
                                                <div class="pendulum-wrapper"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        EOS;
    }else if ($_GET["type"] === "img"){
        echo <<<EOS
            <div class="post-creation-item">
                <div class="delete-uploaded-item absolute">
                    <i class="fa fa-close"></i>
                </div>
                <img src="" class="image-post-uploaded" alt="" style="width: 100%;">
                <input type="hidden" class="pciid" value="0">
            </div>
        EOS;
    }
}else{
    header("location: ../home.php");
}

