<?php
/**
 * Enquiry form, with the static site's behaviour unchanged: main.js validates it and then opens
 * the visitor's own email app with the message addressed to the practice email (Customizer).
 * Nothing is sent or stored by the website, and the page says so. To deliver enquiries from the
 * website itself, replace this widget with a form plugin's form (see the README).
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Enquiry form widget.
 */
class Contact_Form extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-contact-form';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Enquiry Form (email app)', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Form', 'redlynch-dental' ) ) );
		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Send an enquiry', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'intro',
			array(
				'label'       => __( 'Introduction', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Fields marked * are required. Pressing “Prepare email” opens your email app with your message ready to send to {email}. For urgent problems, please call us.', 'redlynch-dental' ),
				'description' => __( '{email} is replaced with the practice email. Keep this explanation: the form does not send anything itself.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'reasons',
			array(
				'label'   => __( '"What is it about?" options (one per line)', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 8,
				'default' => "General enquiry\nNew patient appointment\nCheck-up and clean\nChildren’s dentistry\nCosmetic treatment\nDental implants\nClear aligners\nSomething else",
			)
		);
		$this->add_control(
			'reason_fixed',
			array(
				'label'       => __( 'Fixed subject (hides the "What is it about?" list)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'For landing pages, e.g. "Cosmetic dentistry". Leave empty to show the list above.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'message_label',
			array(
				'label'   => __( 'Message field label', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Your message *', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'consent',
			array(
				'label'       => __( 'Consent text', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'I agree to {practice} using these details to reply to my enquiry. See our {privacy}.', 'redlynch-dental' ),
				'description' => __( '{privacy} becomes a link to the privacy policy.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'button',
			array(
				'label'   => __( 'Button text', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Prepare email', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'variant',
			array(
				'label'   => __( 'Style', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => __( 'Contact page (large heading, lime button)', 'redlynch-dental' ),
					'landing' => __( 'Landing page (smaller heading, dark button)', 'redlynch-dental' ),
				),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s       = $this->get_settings_for_display();
		$email   = rd_practice( 'email' );
		$options = '';
		foreach ( $this->lines( $s['reasons'] ) as $o ) {
			$options .= '<option>' . esc_html( $o ) . '</option>';
		}
		$consent = str_replace(
			array( '{practice}', '{privacy}' ),
			array( esc_html( rd_practice( 'name' ) ), '<a href="' . esc_url( rd_privacy_url() ) . '">' . esc_html__( 'privacy policy', 'redlynch-dental' ) . '</a>' ),
			esc_html( $s['consent'] )
		);
		$landing = 'landing' === ( $s['variant'] ?? '' );
		$heading = $landing ? '<h3>' . esc_html( $s['heading'] ) . '</h3>' : '<h2 style="font-size:1.8rem">' . esc_html( $s['heading'] ) . '</h2>';
		$fixed   = trim( (string) ( $s['reason_fixed'] ?? '' ) );
		$reason  = '' !== $fixed
			? '<input type="hidden" id="f-reason" name="reason" value="' . esc_attr( $fixed ) . '">'
			: '<div class="field full"><label for="f-reason">' . esc_html__( 'What is it about?', 'redlynch-dental' ) . ' <span class="opt">' . esc_html__( '(optional)', 'redlynch-dental' ) . '</span></label><select id="f-reason" name="reason">' . $options . '</select></div>';
		$message = '' !== trim( (string) ( $s['message_label'] ?? '' ) ) ? $s['message_label'] : __( 'Your message *', 'redlynch-dental' );
		$this->out(
			'<form class="form" id="enquiry-form" data-mailto="' . esc_attr( $email ) . '" novalidate aria-describedby="form-intro">
   ' . $heading . '
   <p id="form-intro" class="fine" style="margin-bottom:20px">' . esc_html( str_replace( '{email}', $email, (string) $s['intro'] ) ) . '</p>
   <div class="fields">
    <div class="field"><label for="f-name">' . esc_html__( 'Name *', 'redlynch-dental' ) . '</label><input id="f-name" name="name" autocomplete="name" required><span class="err" data-err="f-name" aria-live="polite"></span></div>
    <div class="field"><label for="f-phone">' . esc_html__( 'Phone *', 'redlynch-dental' ) . '</label><input id="f-phone" name="phone" type="tel" autocomplete="tel" required><span class="err" data-err="f-phone" aria-live="polite"></span></div>
    <div class="field full"><label for="f-email">' . esc_html__( 'Email *', 'redlynch-dental' ) . '</label><input id="f-email" name="email" type="email" autocomplete="email" required><span class="err" data-err="f-email" aria-live="polite"></span></div>
    ' . $reason . '
    <div class="field full"><label for="f-message">' . esc_html( $message ) . '</label><textarea id="f-message" name="message" required></textarea><span class="err" data-err="f-message" aria-live="polite"></span></div>
    <div class="consent"><input id="f-consent" type="checkbox" required><label for="f-consent">' . $consent . '</label></div>
    <span class="err" data-err="f-consent" aria-live="polite" style="grid-column:1/-1;color:var(--ember);font-weight:700;font-size:.85rem"></span>
   </div>
   <button class="btn lg' . ( $landing ? ' dark' : '' ) . ' mt-1" type="submit">' . rd_icon( 'mail' ) . ' ' . esc_html( $s['button'] ) . '</button>
   <div class="form-note" id="form-note" role="status" aria-live="polite"></div>
   <noscript><p class="fine" style="margin-top:12px">' . sprintf(
				/* translators: 1: email link, 2: phone number. */
				esc_html__( 'This form needs JavaScript. Please email %1$s or call %2$s.', 'redlynch-dental' ),
				'<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>',
				esc_html( rd_practice( 'phone' ) )
			) . '</p></noscript>
  </form>'
		);
	}
}
