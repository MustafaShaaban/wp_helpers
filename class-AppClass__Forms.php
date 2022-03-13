<?php
    /**
     * Filename: class-AppClass__Forms.php
     * Description:
     * User: NINJA MASTER - Mustafa Shaaban
     * Date: 11/1/2021
     */

    namespace _APP_\Helpers;

    use _APP_\AppClass_;

    /**
     * Description...
     *
     * @class AppClass__Forms
     * @version 1.0
     * @since 1.0.0
     * @package _APP_
     * @author APPENZA - Mustafa Shaaban
     */
    class AppClass__Forms
    {

        private static ?object $instance = NULL;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
        }

        public static function get_instance()
        {
            $class = __CLASS__;
            if (!self::$instance instanceof $class) {
                self::$instance = new $class;
            }

            return self::$instance;
        }

        /**
         * The Form Controller
         * This function responsible fore controlling the form settings and create all form fields
         * by take them as an array and return the html form.
         *
         * @param array $form_fields
         *
         * @return string
         */
        public function create_form(array $form_fields = [], array $form_tag = []): string
        {
            if (empty($form_fields)) {
                return "";
            }

            $settings = $this->sort_settings($form_fields);

            $form = $this->form_start($form_tag);
            foreach ($settings as $key => $field) {
                if ($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'password' || $field['type'] === 'number') {
                    $form .= $this->std_inputs($field);
                } elseif ($field['type'] === 'hidden') {
                    $form .= $this->create_hidden_inputs($field);
                } elseif ($field['type'] === 'file') {
                    $form .= $this->file_inputs($field);
                } elseif ($field['type'] === 'checkbox') {
                    $form .= $this->checkbox_inputs($field);
                } elseif ($field['type'] === 'radio') {
                    $form .= $this->radio_inputs($field);
                } elseif ($field['type'] === 'switch') {
                    $form .= $this->switch_inputs($field);
                } elseif ($field['type'] === 'textarea') {
                    $form .= $this->textarea_inputs($field);
                } elseif ($field['type'] === 'select') {
                    $form .= $this->selectBox_inputs($field);
                } elseif ($field['type'] === 'nonce') {
                    $form .= $this->create_nonce($field);
                } elseif ($field['type'] === 'submit') {
                    $form .= $this->form_submit_button($field);
                }
            }
            $form .= $this->form_end();
            return $form;
        }

        /**
         * This function responsible for create the start of tag form
         *
         * @param array $args [
         * class    => ''
         * id       => ''
         * ]
         */
        public function form_start(array $args = []): string
        {
            $defaults = [
                    'attr'       => '',
                    'class'      => '',
                    'form_class' => '',
                    'id'         => ''
            ];

            $input_data = array_merge($defaults, $args);

            $classes = explode(' ', $input_data['class']);
            if (!empty($classes)) {
                $classes[0] = $classes[0] . '-container';
            }
            $classes = implode(' ', $classes);

            ob_start();
            ?>
            <div class="<?= AppClass_::_DOMAIN_NAME ?>_form_container <?= $classes ?>">
            <form action="" class="<?= AppClass_::_DOMAIN_NAME ?>_form <?= $input_data['class'] ?> <?= $input_data['form_class'] ?>" id="<?= $input_data['id'] ?>" <?= $input_data['attr'] ?>>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create the form submit button
         *
         * @param array $args [
         * value    => ''
         * class    => ''
         * id       => ''
         * before   => ''
         * after    => ''
         * ]
         *
         */
        public function form_submit_button(array $args = [])
        {
            $defaults = [
                    'type'   => 'submit',
                    'value'  => 'Submit',
                    'class'  => '',
                    'id'     => '',
                    'before' => '',
                    'after'  => '',
                    'order'  => 0
            ];

            $input_data = array_merge($defaults, $args);

            ob_start();
            ?>
            <div class="form-group">
                <?= $input_data['before'] ?>
                <button class="btn btn-primary appclass-btn <?= $input_data['class'] ?>" id="<?= $input_data['id'] ?>"
                        type="submit"><?= $input_data['value'] ?></button>
                <?= $input_data['after'] ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create the end tag of form*
         */
        public function form_end()
        {
            ob_start();
            ?>
            </form>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create stander input field
         *
         * @param array $args [
         * type          => (text / email / password / number)
         * label         => ''
         * name          => ''
         * required      => ''
         * placeholder   => ''
         * class         => ''
         * id            => default is AppClass_::_DOMAIN_NAME_name
         * value         => ''
         * default_value => ''
         * visibility    => ''
         * before        => ''
         * after         => ''
         * autocomplete  => default on
         * hint          => ''
         * abbr          => ''
         * order         => 0
         * extra_attr    => [
         *   'maxlength' => '50',
         *   'minlength' => '',
         *   'max'       => '',
         *   'min'       => '',
         *   ]
         * ]
         *
         * @return string
         *
         */
        public function std_inputs(array $args = []): string
        {
            ob_start();
            $defaults = [
                    'type'          => 'text',
                    'label'         => '',
                    'name'          => '',
                    'required'      => FALSE,
                    'placeholder'   => '',
                    'class'         => '',
                    'id'            => (empty($args['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . $args['name'],
                    'value'         => '',
                    'default_value' => '',
                    'visibility'    => '',
                    'before'        => '',
                    'after'         => '',
                    'autocomplete'  => 'on',
                    'hint'          => '',
                    'abbr'          => __("This field is required", "appclass"),
                    'order'         => 0,
                    'extra_attr'    => [
                            'maxlength' => '50',
                            'minlength' => '',
                            'max'       => '',
                            'min'       => '',
                    ]
            ];

            $input_data = array_merge($defaults, $args);

            $require = (!empty($input_data['required'])) ? '<abbr class="required" title="' . $input_data['abbr'] . '">*</abbr>' : '';
            $value   = (empty($input_data['default_value']) && $input_data['default_value'] !== 0) ? $input_data['value'] : $input_data['default_value'];;

            ?>
            <div class="form-group appclass-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>" class="appclass-label"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <input type="<?= $input_data['type'] ?>"
                       class="form-control appclass-input"
                       id="<?= $input_data['id'] ?>"
                       name="<?= $input_data['name'] ?>"
                       value="<?= $value ?>"
                       autocomplete="<?= $input_data['autocomplete'] ?>"
                       placeholder="<?= $input_data['placeholder'] ?>"
                       aria-describedby="<?= $input_data['id'] . "_help" ?>"
                        <?= $this->create_attr($input_data) ?>
                        <?= $input_data['visibility'] ?>
                        <?= $input_data['required'] ? 'required="require"' : '' ?>>
                <?php
                    if (!empty($input_data['hint'])) {
                        ?>
                        <small id="<?= $input_data['id'] . "_help" ?>" class="form-text text-muted"><?= $input_data['hint'] ?></small><?php
                    }
                ?>
                <?= $input_data['after'] ?>
            </div>
            <?php

            return ob_get_clean();
        }

        /**
         * This function responsible for create the textare field
         *
         * @param array $args [
         * label         => ''
         * name          => ''
         * required      => ''
         * placeholder   => ''
         * class         => ''
         * id            => default is AppClass_::_DOMAIN_NAME_name
         * value         => ''
         * before        => ''
         * after         => ''
         * autocomplete  => default on
         * rows          => '3'
         * hint          => ''
         * abbr          => ''
         * order         => 0
         * extra_attr    => []
         * ]
         *
         * @return string
         *
         */
        public function textarea_inputs(array $args = []): string
        {
            ob_start();
            $defaults   = [
                    'label'        => '',
                    'name'         => '',
                    'required'     => '',
                    'placeholder'  => '',
                    'class'        => '',
                    'id'           => (empty($args['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . $args['name'],
                    'value'        => '',
                    'before'       => '',
                    'after'        => '',
                    'autocomplete' => 'on',
                    'rows'         => '3',
                    'hint'         => '',
                    'abbr'         => __("This field is required", "appclass"),
                    'order'        => 0,
                    'extra_attr'   => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="' . $input_data['abbr'] . '">*</abbr>' : '';
            ?>
            <div class="form-group appclass-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>" class="appclass-label"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <textarea class="form-control appclass-textarea"
                          id="<?= $input_data['id'] ?>"
                          name="<?= $input_data['name'] ?>"
                          placeholder="<?= $input_data['placeholder'] ?>"
                          autocomplete="<?= $input_data['autocomplete'] ?>"
                          rows="<?= $input_data['rows'] ?>"
                          <?= $input_data['required'] ? 'required="require"' : '' ?>
                        <?= $this->create_attr($input_data['extra_attr']) ?>><?= $input_data['value'] ?></textarea>
                <?php
                    if (!empty($input_data['hint'])) {
                        ?>
                        <small id="<?= $input_data['id'] . "_help" ?>" class="form-text text-muted"><?= $input_data['hint'] ?></small><?php
                    }
                ?>
                <?= $input_data['after'] ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create file input field
         *
         * @param array $args [
         * label        => ''
         * name         => ''
         * required     => ''
         * class        => ''
         * id           => default is AppClass_::_DOMAIN_NAME_name
         * before       => ''
         * after        => ''
         * hint         => ''
         * accept       => ''
         * multiple     => ''
         * abbr          => ''
         * order         => 0
         * extra_attr   => []
         * ]
         *
         * @return string
         */
        public function file_inputs(array $args = []): string
        {
            ob_start();
            $defaults = [
                    'label'      => '',
                    'name'       => '',
                    'required'   => '',
                    'class'      => '',
                    'id'         => (empty($args['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . $args['name'],
                    'before'     => '',
                    'after'      => '',
                    'hint'       => '',
                    'accept'     => '',
                    'multiple'   => '',
                    'abbr'       => __("This field is required", "appclass"),
                    'order'      => 0,
                    'extra_attr' => []
            ];

            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="' . $input_data['abbr'] . '">*</abbr>' : '';

            ?>
            <div class="form-group appclass-input-wrapper<?= $input_data['class'] ?>">
                <p><?= $input_data['label'] ?></p>
                <?= $require ?>
                <div class="custom-file">
                    <?= $input_data['before'] ?>
                    <input type="file"
                           class="custom-file-input appclass-input"
                           id="<?= $input_data['id'] ?>"
                           name="<?= $input_data['name'] ?>"
                           aria-describedby="<?= $input_data['id'] . "_help" ?>"
                            <?= $this->create_attr($input_data) ?>
                            <?= $input_data['accept'] ?>
                            <?= $input_data['multiple'] ?>
                            <?= $input_data['required'] ? 'required="require"' : '' ?>>
                    <label class="custom-file-label" for="customFile"><?= $input_data['label'] ?></label>
                </div>
                <?php
                    if (!empty($input_data['hint'])) {
                        ?>
                        <small id="<?= $input_data['id'] . "_help" ?>" class="form-text text-muted"><?= $input_data['hint'] ?></small><?php
                    }
                ?>
                <?= $input_data['after'] ?>
            </div>
            <?php

            return ob_get_clean();
        }

        /**
         * This function responsible for create selectBox input field
         *
         * @param array $args [
         * label          => ''
         * name           => ''
         * required       => ''
         * placeholder    => ''
         * options        => [option_value => option_title]
         * default_option => ''
         * select_option  => ''
         * class          => ''
         * id             => default is AppClass_::_DOMAIN_NAME_name
         * before         => ''
         * after          => ''
         * multiple       => ''
         * abbr          => ''
         * order         => 0
         * extra_attr     => []
         *
         * @return string
         *
         */
        public function selectBox_inputs(array $args = []): string
        {
            ob_start();
            $defaults   = [
                    'label'          => '',
                    'name'           => '',
                    'required'       => '',
                    'placeholder'    => '',
                    'options'        => [],
                    'default_option' => '',
                    'select_option'  => '',
                    'class'          => '',
                    'id'             => (empty($args['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . $args['name'],
                    'before'         => '',
                    'after'          => '',
                    'multiple'       => '',
                    'abbr'           => __("This field is required", "appclass"),
                    'order'          => 0,
                    'extra_attr'     => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="' . $input_data['abbr'] . '">*</abbr>' : '';

            ?>
            <div class="form-group appclass-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <label for="<?= $input_data['id'] ?>" class="appclass-label"><?= $input_data['label'] ?></label>
                <?= $require ?>
                <select class="form-control appclass-input" id="<?= $input_data['id'] ?>"
                        name="<?= $input_data['name'] ?>" <?= $this->create_attr($input_data) ?> <?= $input_data['required'] ? 'required="require"' : '' ?>>
                    <?php
                        if (empty($input_data['default_option']) && empty($input_data['select_option'])) {
                            ?>
                            <option value="" disabled="disabled" selected><?= $input_data['placeholder'] ?></option> <?php
                        }
                        foreach ($input_data['options'] as $value => $title) {
                            ?>
                            <option
                            value="<?= $value ?>" <?= (!empty($input_data['default_option']) && $input_data['default_option'] === $value) ? 'selected' : '' ?>><?= $title ?></option><?php
                        }
                    ?>
                </select>
                <?= $input_data['after'] ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create checkbox input field
         *
         * @param array $args [
         * type'    => 'checkbox',
         * choices' => array(
         *  array(
         *      label'      => ''
         *      name'       => ''
         *      required'   => ''
         *      class'      => ''
         *      id'         => default is AppClass_::_DOMAIN_NAME_name
         *      value'      => ''
         *      before'     => ''
         *      after'      => ''
         *      checked'    => ''
         *      abbr          => ''
         *      order         => 0
         *      extra_attr' => []
         *  )
         * )
         * order         => 0
         * ]
         *
         * @return string
         */
        public function checkbox_inputs(array $args = []): string
        {
            ob_start();
            $defaults   = [
                    'type'    => 'checkbox',
                    'class'   => '',
                    'choices' => [
                            [
                                    'label'      => '',
                                    'name'       => '',
                                    'required'   => '',
                                    'class'      => '',
                                    'id'         => '',
                                    'value'      => '',
                                    'before'     => '',
                                    'after'      => '',
                                    'checked'    => '',
                                    'abbr'       => __("This field is required", "appclass"),
                                    'order'      => 0,
                                    'extra_attr' => []
                            ]
                    ],
                    'before'  => '',
                    'after'   => '',
                    'order'   => 0,
            ];
            $input_data = array_merge($defaults, $args);
            foreach ($input_data['choices'] as $k => $arr) {
                $input_data['choices'][$k] = array_merge($defaults['choices'][0], $arr);
            }

            echo $input_data['before'];

            $count = 0;
            foreach ($input_data['choices'] as $name) {
                $require = (!empty($input_data['required'])) ? '<abbr class="required" title="' . $name['abbr'] . '">*</abbr>' : '';
                if (empty($name['id'])) {
                    $id = (empty($name['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . str_replace('[]', '', $name['name']) . '_' . $count;
                    $count++;
                } else {
                    $id = $name['id'];
                }
                ?>
                <div class="form-check appclass-input-wrapper<?= $name['class'] ?>">
                    <?= $name['before'] ?>
                    <?= $require ?>
                    <input type="<?= $input_data['type'] ?>"
                           class="form-control appclass-checkbox"
                           id="<?= $id ?>"
                           name="<?= $name['name'] ?>"
                           value="<?= $name['value'] ?>" <?= $name['required'] ? 'required="require"' : '' ?> <?= $this->create_attr($name['extra_attr']) ?> <?= $name['checked'] ?>>
                    <label for="<?= $id ?>" class="appclass-label"><?= $name['label'] ?></label>
                    <?= $name['after'] ?>
                </div>
                <?php
            }

            echo $input_data['after'];

            return ob_get_clean();
        }

        /**
         * This function responsible for create radio input field
         *
         * @param array $args [
         * type'    => 'checkbox',
         * name'    => ''
         * choices' => array(
         *  array(
         *      label'      => ''
         *      required'   => ''
         *      class'      => ''
         *      id'         => default is AppClass_::_DOMAIN_NAME_name
         *      value'      => ''
         *      before'     => ''
         *      after'      => ''
         *      checked'    => ''
         *      abbr          => ''
         *      order         => 0
         *      extra_attr' => []
         *  )
         * )
         * order         => 0
         * ]
         *
         * @return string
         */
        public function radio_inputs(array $args = []): string
        {
            ob_start();
            $defaults = [
                    'type'     => 'radio',
                    'title'    => '',
                    'class'    => '',
                    'name'     => '',
                    'before'   => '',
                    'after'    => '',
                    'required' => TRUE,
                    'abbr'     => __("This field is required", "appclass"),
                    'choices'  => [
                            [
                                    'label'      => '',
                                    'class'      => '',
                                    'id'         => (empty($args['choices']['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . $args['choices']['name'],
                                    'value'      => '',
                                    'before'     => '',
                                    'after'      => '',
                                    'checked'    => '',
                                    'order'      => 0,
                                    'extra_attr' => []
                            ]
                    ],
                    'order'    => 0,
            ];

            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="' . $input_data['abbr'] . '">*</abbr>' : '';

            echo $input_data['before'];

            ?>
            <div class="<?= $input_data['class'] ?>"><?php
            ?><label><?= $require ?> <?= $input_data['title'] ?></label><?php

            $count = 0;
            foreach ($input_data['choices'] as $name) {
                if (empty($name['id'])) {
                    $id = (empty($name['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . str_replace('[]', '', $name['name']) . $count;
                    $count++;
                } else {
                    $id = $name['id'];
                }
                ?>
                <div class="form-check appclass-input-wrapper <?= $name['class'] ?>">
                    <?= $name['before'] ?>
                    <input type="<?= $input_data['type'] ?>"
                           class="form-control appclass-radio"
                           id="<?= $id ?>"
                           name="<?= $input_data['name'] ?>"
                           value="<?= $name['value'] ?>" <?= $input_data['required'] ? 'required="require"' : '' ?> <?= $this->create_attr($name['extra_attr']) ?> <?= $name['checked'] ? 'checked' : '' ?>>
                    <label for="<?= $id ?>" class="appclass-label"><?= $name['label'] ?></label>
                    <?= $name['after'] ?>
                </div>
                <?php
            }

            ?></div><?php

            echo $input_data['after'];

            return ob_get_clean();
        }

        /**
         * This function responsible for create radio input field
         *
         * @param array $args [
         * type'       => 'switch',
         * label'      => ''
         * name'       => ''
         * required'   => ''
         * class'      => ''
         * id'         => default is AppClass_::_DOMAIN_NAME_name
         * before'     => ''
         * after'      => ''
         * checked'    => ''
         * abbr          => ''
         * order         => 0
         * extra_attr' => []
         * ]
         *
         * @return string
         */
        public function switch_inputs(array $args = []): string
        {
            ob_start();
            $defaults   = [
                    'type'       => 'switch',
                    'label'      => '',
                    'name'       => '',
                    'required'   => '',
                    'class'      => '',
                    'id'         => (empty($args['name'])) ? "" : AppClass_::_DOMAIN_NAME . '_' . $args['name'],
                    'before'     => '',
                    'after'      => '',
                    'checked'    => '',
                    'abbr'       => __("This field is required", "appclass"),
                    'order'      => 0,
                    'extra_attr' => []
            ];
            $input_data = array_merge($defaults, $args);
            $require    = (!empty($input_data['required'])) ? '<abbr class="required" title="' . $input_data['abbr'] . '">*</abbr>' : '';

            ?>

            <div class="custom-control custom-switch appclass-input-wrapper <?= $input_data['class'] ?>">
                <?= $input_data['before'] ?>
                <?= $require ?>
                <input type="checkbox"
                       class="custom-control-input appclass-input appclass-switch <?= AppClass_::_DOMAIN_NAME . '-' . $input_data['class'] ?>"
                       id="<?= $input_data['id'] ?>"
                       name="<?= $input_data['name'] ?>"
                        <?= $input_data['required'] ? 'required="require"' : '' ?> <?= $this->create_attr($input_data) ?> <?= $input_data['checked'] ?>>
                <label class="custom-control-label" for="<?= $input_data['id'] ?>"><?= $input_data['label'] ?></label>
                <?= $input_data['after'] ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * This function responsible for create extra html attributes.
         *
         * @param array $args
         *
         * @return string
         */
        public function create_attr(array $args = []): string
        {
            $attrs = '';
            if (isset($args['extra_attr']) && is_array($args['extra_attr']) && !empty($args['extra_attr'])) {
                foreach ($args['extra_attr'] as $name => $value) {

                    if (isset($args['type'])) {
                        if ($args['type'] === 'number' && $name === 'maxlength' || $name === 'minlength') {
                            continue;
                        }
                        if ($args['type'] !== 'number' && $name === 'max' || $name === 'min') {
                            continue;
                        }
                    }

                    $attrs .= " $name='$value' ";
                }
            }

            return $attrs;
        }

        /**
         * This function responsible for creating the input field
         *
         * @param array $args
         *
         * @return string
         */
        public function create_hidden_inputs(array $args = []): string
        {
            ob_start();
            $inputs = '';
            if (!empty($args)) {
                foreach ($args as $input) {
                    $name   = $input['name'];
                    $value  = $input['value'];
                    $inputs .= "<input type='hidden' name='$name' value='$value'/>";
                }
                $inputs .= ob_get_clean();
            }

            return $inputs;
        }

        /**
         * This function responsible for creating the wp nonce
         *
         * @param array $args
         *
         * @return string
         */
        public function create_nonce(array $args): string
        {
            $defaults = [
                    'type'  => 'nonce',
                    'name'  => '',
                    'vale'  => '',
                    'order' => 0
            ];

            $nonce_data = array_merge($defaults, $args);

            return wp_nonce_field($nonce_data['value'], $nonce_data['name']);
        }

        /**
         * This functions responsible for sort inputs
         *
         * @param array $settings
         *
         * @return array
         */
        private function sort_settings(array $settings = []): array
        {
            foreach ($settings as $key => $value) {
                if ($value['type'] === 'checkbox' && isset($value['choices']) && !empty($value['choices'])) {
                    $choices = $value['choices'];
                    usort($choices, function($a, $b) {
                        return $a['order'] > $b['order'];
                    });
                    $settings[$key]['choices'] = $choices;
                }
            }
            usort($settings, function($a, $b) {
                return $a['order'] > $b['order'];
            });

            return $settings;
        }
    }
