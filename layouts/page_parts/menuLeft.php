<?php
    echo <<<LE
            <section class="left-nav-bar col-md-3">
                <ul class="menu-main">
                    <li >
                        <a href="$root/home.php" data-name="home" class="active" style="--i:var(--bs-red);">
                            <i class="fad fa-house"></i>
                            <span>Feed</span>
                        </a>
                    </li>
                    <li>
                        <a href="home.php" data-name="people" style="--i:var(--bs-green);">
                            <i class="fad fa-user-group"></i>
                            <span>Friends</span>
                        </a>
                    </li>
                    <li>
                        <a href="video.php" style="--i:var(--bs-cyan);">
                            <i class="fad fa-video"></i>
                            <span>Videos</span>
                        </a>
                    </li>
                    <li>
                        <a data-name="chat" href="$root/chat.php" style="--i:var(--bs-orange);">
                            <i class="fad fa-paper-plane"></i>
                            <span>Messages</span>
                        </a>
                    </li>
                    <li>
                        <a href="home.php" style="--i:blue;">
                            <i class="fad fa-bell"></i>
                            <span>Notifications</span>
                            <div class="new">12</div>
                        </a>
                    </li>
                    <li>
                        <a href="home.php" style="--i:var(--bs-pink);">
                            <i class="fad fa-search"></i>
                            <span>Search</span>
                        </a>
                    </li>
                    <li>
                        <a href="home.php" style="--i:var(--bs-pink);">
                            <i class="fad fa-shopping-cart"></i>
                            <span>Marketplace</span>
                        </a>
                    </li>
                </ul>
            </section>
LE;
