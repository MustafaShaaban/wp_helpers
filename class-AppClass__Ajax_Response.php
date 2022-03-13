<?php
    /**
     * Filename: class-AppClass__Ajax_Response.php
     * Description:
     * User: NINJA MASTER - Mustafa Shaaban
     * Date: 11/11/2021
     */

    namespace _App_\Helpers;

    /**
     * Description...
     *
     * @class AppClass__Ajax_Response
     * @version 1.0
     * @since 1.0.0
     * @package appclass
     * @author APPENZA - Mustafa Shaaban
     */
	class AppClass__Ajax_Response
	{

		public function __construct(bool $status, string $msg, array $data = [])
		{
			$this->response($status, $msg, $data);
		}

		private function response(bool $status, string $msg, array $data = [])
		{
			$response = [
				'success' => $status,
				'msg'    => $msg,
			];

			if (!empty($data)) {
				$response['data'] = $data;
			}

			wp_send_json($response);
		}

	}
