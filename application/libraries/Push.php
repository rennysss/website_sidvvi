<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Avisos Web Push a quienes activaron la campana en el panel */
class Push
{
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * @param string $role  all | superadmin | editor | viewer
	 * @return int  cuántos navegadores lo recibieron
	 */
	public function send_to_role($role, $title, $body, $url = NULL)
	{
		$public = env('VAPID_PUBLIC');
		$private = env('VAPID_PRIVATE');
		if ( ! $public || ! $private) {
			log_message('error', 'Push: faltan VAPID_PUBLIC y VAPID_PRIVATE en .env');
			return 0;
		}

		$this->CI->db->select('ps.*')->from('push_subscriptions ps');
		if ($role && $role !== 'all') {
			$this->CI->db->join('users u', 'u.id = ps.user_id', 'inner');
			$this->CI->db->where('u.role', $role)->where('u.is_active', 1)->where('u.deleted_at IS NULL', NULL, FALSE);
		}
		$subs = $this->CI->db->get()->result();
		if ( ! $subs) return 0;

		try {
			$web = new \Minishlink\WebPush\WebPush(array(
				'VAPID' => array(
					'subject'    => 'mailto:'.env('MAIL_FROM', 'no-reply@sidvvi.com'),
					'publicKey'  => $public,
					'privateKey' => $private,
				),
			));
		} catch (\Throwable $e) {
			log_message('error', 'Push: '.$e->getMessage());
			return 0;
		}

		$payload = json_encode(array(
			'title' => $title,
			'body'  => $body,
			'url'   => $url ?: admin_url('dashboard'),
		), JSON_UNESCAPED_UNICODE);

		foreach ($subs as $s) {
			$web->queueNotification(
				\Minishlink\WebPush\Subscription::create(array(
					'endpoint'        => $s->endpoint,
					'contentEncoding' => 'aes128gcm',
					'keys'            => array('p256dh' => $s->p256dh, 'auth' => $s->auth),
				)),
				$payload
			);
		}

		$sent = 0;
		foreach ($web->flush() as $report) {
			$endpoint = $report->getEndpoint();
			if ($report->isSuccess()) {
				$sent++;
				continue;
			}
			if ($report->isSubscriptionExpired()) {
				$this->CI->db->where('endpoint_hash', hash('sha256', $endpoint))->delete('push_subscriptions');
			} else {
				log_message('error', 'Push: '.$report->getReason());
			}
		}
		return $sent;
	}
}
