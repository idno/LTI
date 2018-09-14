<?php

    namespace IdnoPlugins\LTI\Pages
    {

        use Idno\Common\Page;

        class Admin extends Page {

            function getContent() {

                $this->adminGatekeeper();

                $lti = \Idno\Core\site()->plugins()->get('LTI'); /* @var \IdnoPlugins\LTI\Main $lti */
                $lti->loadLTI();

                $consumers = $lti->db_connector->Tool_Consumer_list();

                $t = \Idno\Core\site()->template();
                $body = $t->__(['consumers' => $consumers])->draw('lti/admin');
                $t->__(array('title' => 'LTI', 'body' => $body))->drawPage();

            }

            function postContent() {

                $this->adminGatekeeper();
                $action = $this->getInput('action');

                $lti = \Idno\Core\site()->plugins()->get('LTI'); /* @var \IdnoPlugins\LTI\Main $lti */
                $lti->loadLTI();

                switch($action) {

                    case 'create':
                        $name = $this->getInput('name');
                        if (empty($name)) {
                            $name = 'New LTI connection';
                        }
                        $key = md5(time() . rand(0,999) . \Idno\Core\site()->config()->site_secret);
                        $consumer = new \LTI_Tool_Consumer($key, $lti->db_connector);
                        $consumer->name = $name;
                        $consumer->secret = md5(time() . rand(0,999) . \Idno\Core\site()->config()->site_secret);
                        $consumer->enabled = true;
                        $consumer->save();

                        break;
                    case 'delete':

                        $key = $this->getInput('key');
                        if ($consumers = $lti->db_connector->Tool_Consumer_list()) {
                            foreach($consumers as $consumer) {
                                if ($consumer->getKey() == $key) {
                                    $consumer->delete();
                                }
                            }
                        }

                        break;

                }

                $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'admin/lti/');

            }

        }

    }