<?php

    namespace IdnoPlugins\LTI\Pages {

        use Idno\Common\Page;

        class Forwarder extends Page {

            function getContent() {

                if (\Idno\Core\site()->session()->isLoggedIn()) {
                    $this->forward();
                }

                echo \Idno\Core\site()->template()->draw('lti/forwarder');

            }

        }

    }