<?php
    /**
     * Filename: class-AppClass__Hooks.php
     * Description:
     * User: NINJA MASTER - Mustafa Shaaban
     * Date: 11/1/2021
     */

    namespace _APP_\Helpers;

    use _APP_\AppClass_;

    /**
     * Description...
     *
     * @class AppClass__Hooks
     * @version 1.0
     * @since 1.0.0
     * @package _APP_
     * @author APPENZA - Mustafa Shaaban
     */
    class AppClass__Hooks
    {
        /**
         * The array of actions registered with WordPress.
         *
         * @since    1.0.0
         * @access   protected
         * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
         */
        private array $actions = [];

        /**
         * The array of filters registered with WordPress.
         *
         * @since    1.0.0
         * @access   protected
         * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
         */
        private array $filters = [];

        /**
         * @var array
         */
        private array $styles = [];

        /**
         * @var array
         */
        private array $scripts = [];

        /**
         * @var array
         */
        private array $localizations = [];

        /**
         * @var array
         */
        private array $short_codes = [];

        /**
         * @var string
         */
        private string $prefix;

        /**
         * paths
         * @cons array
         */
        const PATHS = [
            'root'   => [
                'css' => THEME_URI,
            ],
            'public' => [
                'js'      => THEME_URI . '/public/js',
                'img'     => THEME_URI . '/public/img',
                'vendors' => THEME_URI . '/public/vendors'
            ],
            'admin'  => [
                'js'      => THEME_URI . '/admin/js',
                'css'     => THEME_URI . '/admin/css',
                'img'     => THEME_URI . '/admin/img',
                'vendors' => THEME_URI . '/admin/vendors'
            ],
        ];

        /**
         * Initialize the collections used to maintain the actions and filters.
         *
         * @since    1.0.0
         */
        public function __construct()
        {
            $this->prefix = "development" === AppClass_::_ENVIRONMENT ? "" : ".min";
        }

        /**
         * Add a new action to the collection to be registered with WordPress.
         *
         * @param string $hook The name of the WordPress action that is being registered.
         * @param object $component A reference to the instance of the object on which the action is defined.
         * @param string $callback The name of the function definition on the $component.
         * @param int    $priority Optional. The priority at which the function should be fired. Default is 10.
         * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
         *
         * @since    1.0.0
         */
        public function add_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
        {
            $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
        }

        /**
         * Add a new filter to the collection to be registered with WordPress.
         *
         * @param string $hook The name of the WordPress filter that is being registered.
         * @param object $component A reference to the instance of the object on which the filter is defined.
         * @param string $callback The name of the function definition on the $component.
         * @param int    $priority Optional. The priority at which the function should be fired. Default is 10.
         * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1
         *
         * @since    1.0.0
         */
        public function add_filter(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
        {
            $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
        }

        /**
         * Add a new short code to the collection to be registered with WordPress.
         *
         * @param string $hook
         * @param object $component
         * @param string $callback
         */
        public function add_shortcode(string $hook, object $component, string $callback)
        {
            $this->short_codes = $this->add_code($this->short_codes, $hook, $component, $callback);
        }

        /**
         * Add a new style to the collection to be registered with WordPress.
         *
         * @param string $script_name
         * @param string $path
         * @param array  $dependencies
         * @param null   $version
         * @param null   $media
         */
        public function add_style(string $script_name, string $path, array $dependencies = [], $version = NULL, $media = NULL): void
        {
            $this->styles = $this->enqueue($this->styles, $script_name, $path, $dependencies, $version, $media);
        }

        /**
         * Add a new script to the collection to be registered with WordPress.
         *
         * @param string $script_name
         * @param string $path
         * @param array  $dependencies
         * @param null   $version
         * @param null   $position
         */
        public function add_script(string $script_name, string $path, array $dependencies = [], $version = NULL, $position = NULL): void
        {
            $this->scripts = $this->enqueue($this->scripts, $script_name, $path, $dependencies, $version, $position);
        }

        /**
         * Add a new localization to the collection to be registered with WordPress.
         *
         * @param string $handle
         * @param string $object_name
         * @param array  $object_values
         */
        public function add_localization(string $handle, string $object_name, array $object_values)
        {
            $this->localizations = $this->add_local($this->localizations, $handle, $object_name, $object_values);
        }

        /**
         * A utility function that is used to register the actions and hooks into a single
         * collection.
         *
         * @param array  $hooks The collection of hooks that is being registered (that is, actions or filters).
         * @param string $hook The name of the WordPress filter that is being registered.
         * @param object $component A reference to the instance of the object on which the filter is defined.
         * @param string $callback The name of the function definition on the $component.
         * @param int    $priority The priority at which the function should be fired.
         * @param int    $accepted_args The number of arguments that should be passed to the $callback.
         *
         * @return   array The collection of actions and filters registered with WordPress.
         * @since    1.0.0
         * @access   private
         */
        private function add(array $hooks, string $hook, object $component, string $callback, int $priority, int $accepted_args): array
        {

            $hooks[] = [
                'hook'          => $hook,
                'component'     => $component,
                'callback'      => $callback,
                'priority'      => $priority,
                'accepted_args' => $accepted_args
            ];

            return $hooks;

        }

        /**
         * A utility function that is used to register the shortcode and hooks into a single
         * collection.
         *
         * @param array  $hooks
         * @param string $hook
         * @param object $component
         * @param string $callback
         *
         * @return array
         */
        private function add_code(array $hooks, string $hook, object $component, string $callback): array
        {
            $hooks[] = [
                'hook'      => $hook,
                'component' => $component,
                'callback'  => $callback
            ];

            return $hooks;

        }

        /**
         * A utility function that is used to register the script hooks into a single
         * collection.
         *
         * @param array  $hooks
         * @param string $script_name
         * @param string $path
         * @param array  $dependencies
         * @param null   $version
         * @param null   $position_media
         *
         * @return array
         */
        private function enqueue(array $hooks, string $script_name, string $path, array $dependencies, $version = NULL, $position_media = NULL): array
        {

            $hooks[] = [
                'script_name'  => $script_name,
                'path'         => $path,
                'dependencies' => !empty($dependencies) ? $dependencies : [],
                'media'        => !empty($position_media) ? $position_media : FALSE,
                'position'     => !empty($position_media) ? $position_media : 'all',
                'version'      => !empty($version) ? $version : AppClass_::_VERSION
            ];

            return $hooks;

        }

        /**
         * A utility function that is used to register the localizations into a single
         * collection.
         *
         * @param array  $hooks
         * @param string $handle
         * @param string $object_name
         * @param array  $object_values
         *
         * @return array
         */
        private function add_local(array $hooks, string $handle, string $object_name, array $object_values): array
        {
            $hooks[] = [
                'handle'        => $handle,
                'object_name'   => $object_name,
                'object_values' => $object_values
            ];

            return $hooks;

        }

        /**
         * Register the filters and actions with WordPress.
         *
         * @since    1.0.0
         */
        public function run(): void
        {
            if (!empty($this->filters)) {
                foreach ($this->filters as $hook) {
                    add_filter($hook['hook'], [
                        $hook['component'],
                        $hook['callback']
                    ], $hook['priority'], $hook['accepted_args']);
                }
            }

            if (!empty($this->actions)) {
                foreach ($this->actions as $hook) {
                    add_action($hook['hook'], [
                        $hook['component'],
                        $hook['callback']
                    ], $hook['priority'], $hook['accepted_args']);
                }
            }

            if (!empty($this->short_codes)) {
                foreach ($this->short_codes as $hook) {
                    add_shortcode($hook['hook'], [
                        $hook['component'],
                        $hook['callback']
                    ]);
                }
            }

            if (!empty($this->styles)) {
                foreach ($this->styles as $hook) {
                    wp_enqueue_style($hook['script_name'], $hook['path'] . $this->prefix . '.css', $hook['dependencies'], $hook['version'], $hook['media']);
                }
            }

            if (!empty($this->scripts)) {
                foreach ($this->scripts as $hook) {
                    wp_enqueue_script($hook['script_name'], $hook['path'] . $this->prefix . '.js', $hook['dependencies'], $hook['version'], $hook['position']);
                }
            }

            if (!empty($this->localizations)) {
                foreach ($this->localizations as $hook) {
                    wp_localize_script($hook['handle'], $hook['object_name'], $hook['object_values']);
                }
            }

        }
    }
