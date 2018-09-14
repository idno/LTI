<?php

    namespace IdnoPlugins\LTI {

        use Idno\Entities\User;

        class KnownToolProvider extends \LTI_Tool_Provider {

            public $debugMode = true;

            function onLaunch() {

                $msg = var_export($_REQUEST,true) . "\n\n\n" . var_export($_SERVER,true) . "\n\n\n" . var_export(@file_get_contents('php://input'),true);
                $msg = str_replace('tumtumrub5!','---',$msg);
                $msg .= "\n\n\n" . var_export($this->user,true);

                if (!empty($this->user)) {
                    if ($email = $this->user->email && $id = $this->user->getId()) {
                        $handle = 'user' . $id;
                        if ($user = User::getByHandle($handle)) {

                            $msg .= "\n\nLTI found user. Id was {$id}, found {$handle} at " . $user->getTitle();

                            \Idno\Core\site()->session()->logUserOn($user);

                            //mail('hello@withknown.com','LTI LAUNCH', $msg);

                            $time = time();
                            $token = openssl_encrypt(\Idno\Core\site()->config()->site_secret . $time . $user->getID());
                            //\Idno\Core\site()->currentPage()->forward(\Idno\Core\site()->config()->getDisplayURL() . 'lti/forwarder?token=' . $token . '&id=' . $user->getID() . '&time=' . $time);
                            \Idno\Core\site()->currentPage()->forward(\Idno\Core\site()->config()->getDisplayURL() . 'lti/forwarder/');
                        } else {

                            $msg .= "\n\nLTI made user. Id was {$id}, deployed {$handle} at new name";

                            $user         = new \Idno\Entities\User();
                            $user->email  = $email;
                            $user->handle = strtolower(trim($handle)); // Trim the handle and set it to lowercase
                            $user->setPassword('');
                            $user->notifications['email'] = 'all';
                            if (empty($name)) {
                                $name = $user->handle;
                            }
                            $user->setTitle($this->user->fullname);
                            \Idno\Core\site()->triggerEvent('site/newuser', array('user' => $user)); // Event hook for new user
                            $user->save();
                            \Idno\Core\site()->session()->logUserOn($user);
                            \Idno\Core\site()->session()->addMessage("Welcome to Known! As this is your first time here, why not try posting a status update to say hello?");

                            //mail('hello@withknown.com','LTI LAUNCH', $msg);

                            \Idno\Core\site()->currentPage()->forward(\Idno\Core\site()->config()->getDisplayURL() . 'lti/forwarder/');
                        }
                    }
                }

                $msg .= "\n\nThere was an error connecting.";
                //mail('hello@withknown.com','LTI FAIL', $msg);

                $this->onError();

            }

            function onError() {

                \Idno\Core\site()->session()->logUserOff();
                $t = \Idno\Core\site()->template();
                $t->__(['title' => 'Something went wrong', 'body' => $t->draw('lti/error')])->drawPage();

            }

        }

    }