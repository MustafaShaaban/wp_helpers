<?php
    /**
     * Filename: class-AppClass__Mail.php
     * Description:
     * User: NINJA MASTER - Mustafa Shaaban
     * Date: 11/1/2021
     */

    namespace _APP_\Helpers;

    /**
     * @class AppClass__Mail
     */
    class AppClass__Mail
    {

        private array  $to          = [];
        private array  $cc          = [];
        private array  $bcc         = [];
        private array  $headers     = [];
        private array  $attachments = [];
        private bool   $sendAsHTML  = TRUE;
        private string $subject     = '_APP_';
        private string $from        = '';

        private string $templateStart         = THEME_PATH . '/email-template/';
        private string $headerDefaultTemplate = THEME_PATH . '/email-template/default/header';
        private        $headerTemplate        = FALSE;
        private array  $headerVariables       = [];
        private string $defaultTemplate       = THEME_PATH . '/email-template/default/body';
        private        $template              = FALSE;
        private array  $variables             = [];
        private string $footerDefaultTemplate = THEME_PATH . '/email-template/default/footer';
        private        $footerTemplate        = FALSE;
        private array  $footerVariables       = [];

        public static function init(): AppClass__Mail
        {
            return new self;
        }

        /**
         * @param $to
         *
         * @return $this
         */
        public function to($to): AppClass__Mail
        {
            if (is_array($to)) {
                $this->to = $to;
            } else {
                $this->to = [ $to ];
            }
            return $this;
        }

        /**
         * Get recipients
         * @return array $to
         */
        public function getTo(): array
        {
            return $this->to;
        }

        /**
         * Set Cc recipients
         *
         * @param String|array $cc
         *
         * @return AppClass__Mail $this
         */
        public function cc($cc): AppClass__Mail
        {
            if (is_array($cc)) {
                $this->cc = $cc;
            } else {
                $this->cc = [ $cc ];
            }
            return $this;
        }

        /**
         * Get Cc recipients
         * @return array $cc
         */
        public function getCc(): array
        {
            return $this->cc;
        }

        /**
         * Set Email Bcc recipients
         *
         * @param String|array $bcc
         *
         * @return AppClass__Mail $this
         */
        public function bcc($bcc): AppClass__Mail
        {
            if (is_array($bcc)) {
                $this->bcc = $bcc;
            } else {
                $this->bcc = [ $bcc ];
            }

            return $this;
        }

        /**
         * Set email Bcc recipients
         * @return array $bcc
         */
        public function getBcc(): array
        {
            return $this->bcc;
        }

        /**
         * Set email Subject
         *
         * @param string $subject
         *
         * @return AppClass__Mail $this
         */
        public function subject(string $subject): AppClass__Mail
        {
            $this->subject = sprintf(__('%s - %s'), $this->subject, $subject);
            return $this;
        }

        /**
         * Returns email subject
         * @return string
         */
        public function getSubject(): string
        {
            return $this->subject;
        }

        /**
         * Set From header
         *
         * @param String
         *
         * @return AppClass__Mail $this
         */
        public function from($from): AppClass__Mail
        {
            $this->from = $from;
            return $this;
        }

        /**
         * Set the email's headers
         *
         * @param String|array $headers [description]
         *
         * @return AppClass__Mail $this
         */
        public function headers($headers): AppClass__Mail
        {
            if (is_array($headers)) {
                $this->headers = $headers;
            } else {
                $this->headers = [ $headers ];
            }

            return $this;
        }

        /**
         * Returns headers
         * @return array
         */
        public function getHeaders(): array
        {
            return $this->headers;
        }

        /**
         * Returns email content type
         * @return String
         */
        public function HTMLFilter(): string
        {
            return 'text/html';
        }

        /**
         * Set email content type
         *
         * @param Bool $html
         *
         * @return AppClass__Mail $this
         */
        public function sendAsHTML(bool $html): AppClass__Mail
        {
            $this->sendAsHTML = $html;
            return $this;
        }

        /**
         * Attach a file or array of files.
         * Filepaths must be absolute.
         *
         * @param String|array $path
         *
         * @return AppClass__Mail $this
         * @throws \Exception
         */
        public function attach($path): AppClass__Mail
        {
            if (is_array($path)) {
                $this->attachments = [];
                foreach ($path as $path_) {
                    if (!file_exists($path_)) {
                        throw new \Exception("Attachment not found at $path");
                    } else {
                        $this->attachments[] = $path_;
                    }
                }
            } else {
                if (!file_exists($path)) {
                    throw new \Exception("Attachment not found at $path");
                }
                $this->attachments = [ $path ];
            }

            return $this;
        }

        /**
         * Set the before-template file
         *
         * @param String     $template Path to HTML template
         * @param array|null $variables
         *
         * @return AppClass__Mail $this
         * @throws \Exception
         */
        public function templateHeader(string $template, array $variables = NULL): AppClass__Mail
        {
            $template = $this->templateStart . $template . '.php';

            if (!file_exists($template)) {
                throw new \Exception('Template file not found');
            }

            if (is_array($variables)) {
                $this->headerVariables = $variables;
            }

            $this->headerTemplate = $template;
            return $this;
        }

        /**
         * Set the template file
         *
         * @param String     $template Path to HTML template
         * @param array|null $variables
         *
         * @return AppClass__Mail $this
         * @throws \Exception
         */
        public function template(string $template, array $variables = NULL): AppClass__Mail
        {
            $template = $this->templateStart . $template . '.php';

            if (!file_exists($template)) {
                throw new \Exception('File not found');
            }

            if (is_array($variables)) {
                $this->variables = $variables;
            }

            $this->template = $template;
            return $this;
        }

        /**
         * Set the after-template file
         *
         * @param String     $template Path to HTML template
         * @param array|null $variables
         *
         * @return AppClass__Mail $this
         * @throws \Exception
         */
        public function templateFooter(string $template, array $variables = NULL): AppClass__Mail
        {
            $template = $this->templateStart . $template . '.php';

            if (!file_exists($template)) {
                throw new \Exception('Template file not found');
            }

            if (is_array($variables)) {
                $this->footerVariables = $variables;
            }

            $this->footerTemplate = $template;
            return $this;
        }

        /**
         * Renders the template
         * @return String
         * @throws \Exception
         */
        public function render(): string
        {
            return $this->renderPart('before') . $this->renderPart('main') . $this->renderPart('after');
        }

        /**
         * Render a specific part of the email
         *
         * @param String $part before, after, main
         *
         * @return String
         * @throws \Exception
         * @author Anthony Budd
         */
        public function renderPart(string $part = 'main'): string
        {
            switch ($part) {
                case 'before':
                    $templateFile = empty($this->headerTemplate) ? $this->headerDefaultTemplate . '.php' : $this->headerTemplate;
                    $variables    = $this->headerVariables;
                    break;

                case 'after':
                    $templateFile = empty($this->footerTemplate) ? $this->footerDefaultTemplate . '.php' : $this->footerTemplate;
                    $variables    = $this->footerVariables;
                    break;

                case 'main':
                default:
                    $templateFile = empty($this->template) ? $this->defaultTemplate . '.php' : $this->template;
                    $variables    = $this->variables;
                    break;
            }

            if ($templateFile === FALSE) {
                return '';
            }


            $extension = strtolower(pathinfo($templateFile, PATHINFO_EXTENSION));
            if ($extension === 'php') {

                ob_start();
                ob_clean();

                foreach ($variables as $key => $value) {
                    $$key = $value;
                }

                include $templateFile;

                $html = ob_get_clean();

                return $html;

            } elseif ($extension === 'html') {

                $template = file_get_contents($templateFile);

                if (!is_array($variables) || empty($variables)) {
                    return $template;
                }

                return $this->parseAsMustache($template, $variables);

            } else {
                throw new \Exception("Unknown extension {$extension} in path '{$templateFile}'");
            }
        }

        public function buildSubject()
        {
            return $this->parseAsMustache($this->subject, array_merge($this->headerVariables, $this->variables, $this->footerVariables));
        }

        public function parseAsMustache($string, $variables = [])
        {

            preg_match_all('/{{\s*.+?\s*}}/', $string, $matches);

            foreach ($matches[0] as $match) {
                $var = str_replace('{', '', str_replace('}', '', preg_replace('/\s+/', '', $match)));

                if (isset($variables[$var]) && !is_array($variables[$var])) {
                    $string = str_replace($match, $variables[$var], $string);
                }
            }

            return $string;
        }

        /**
         * Builds Email Headers
         * @return String email headers
         */
        public function buildHeaders(): string
        {
            $headers = '';

            $headers .= implode("\r\n", $this->headers) . "\r\n";

            foreach ($this->bcc as $bcc) {
                $headers .= sprintf("Bcc: %s \r\n", $bcc);
            }

            foreach ($this->cc as $cc) {
                $headers .= sprintf("Cc: %s \r\n", $cc);
            }

            if (!empty($this->from)) {
                $headers .= sprintf("From: %s \r\n", $this->from);
            }

            return $headers;
        }

        /**
         * Sends a rendered email using
         * WordPress's wp_mail() function
         * @return Bool
         * @throws \Exception
         */
        public function send(): bool
        {
            if (count($this->to) === 0) {
                throw new \Exception('You must set at least 1 recipient');
            }

//            if (empty($this->template)) {
//                throw new \Exception('You must set a template');
//            }

            if ($this->sendAsHTML) {
                add_filter('wp_mail_content_type', [
                    $this,
                    'HTMLFilter'
                ]);
            }

            return wp_mail($this->to, $this->buildSubject(), $this->render(), $this->buildHeaders(), $this->attachments);
        }
    }
