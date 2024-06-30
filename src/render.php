<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Generate unique id for labels.
$unique_id = wp_unique_id( 'booking-form-' );

// Compose steps data.
$steps = [
	[
		'id'       => 1,
		'title'    => __( 'Enter your personal details', 'booking-form' ),
		'subtitle' => __( 'Personal details', 'booking-form' ),
		'isActive' => true,
		'fields'   => [
			[
				'id'       => "{$unique_id}-field-1",
				'type'     => 'text',
				'name'     => 'name',
				'label'    => __( 'Name', 'booking-form' ),
				'value'    => '',
				'required' => true,
			],
			[
				'id'       => "{$unique_id}-field-2",
				'type'     => 'email',
				'name'     => 'email',
				'label'    => __( 'E-mail', 'booking-form' ),
				'value'    => '',
				'required' => true,
			],
			[
				'id'       => "{$unique_id}-field-3",
				'type'     => 'tel',
				'name'     => 'phone',
				'label'    => __( 'Phone', 'booking-form' ),
				'value'    => '',
				'required' => false,
			],
		],
	],
	[
		'id'       => 2,
		'title'    => __( 'Provide your booking details', 'booking-form' ),
		'subtitle' => __( 'Booking details', 'booking-form' ),
		'isActive' => false,
		'fields'   => [
			[
				'id'       => "{$unique_id}-field-4",
				'type'     => 'date',
				'name'     => 'date',
				'label'    => __( 'Date', 'booking-form' ),
				'value'    => '',
				'required' => true,
			],
			[
				'id'       => "{$unique_id}-field-5",
				'type'     => 'time',
				'name'     => 'time',
				'label'    => __( 'Time', 'booking-form' ),
				'value'    => '',
				'required' => true,
			],
		],
	],
	[
		'id'       => 3,
		'title'    => __( 'Review and confirm your booking', 'booking-form' ),
		'subtitle' => __( 'Review and confirm', 'booking-form' ),
		'isActive' => false,
		'fields'   => [],
	],
];

?>

<div
	<?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="booking-form"
	<?php
	echo wp_interactivity_data_wp_context(
		[
			'steps'        => $steps,
			'submitting'   => false,
			'submitted'    => false,
			'errorMessage' => '',
		]
	);
	?>
>
	<div class="booking-form">
		<div class="booking-form__header">
			<template data-wp-each--step="context.steps" data-wp-each-key="context.step.id">
				<div class="booking-form__header-title" data-wp-text="context.step.title" data-wp-bind--hidden="!context.step.isActive"></div>
			</template>
		</div>

		<div class="booking-form__progress">
			<template data-wp-each--step="context.steps" data-wp-each-key="context.step.id">
				<div class="booking-form__progress-tab">
					<div class="booking-form__progress-tab-info" data-wp-text="context.step.subtitle" data-step="context.step.id" data-wp-class--booking-form__progress-tab-info--active="context.step.isActive"></div>
				</div>
			</template>
		</div>

		<div class="booking-form__body">
			<div class="booking-form__alert booking-form__alert--danger" data-wp-bind--hidden="!context.errorMessage">
				<div class="booking-form__alert-message" data-wp-text="context.errorMessage"></div>
			</div>

			<template data-wp-each--step="context.steps" data-wp-each-key="context.step.id">
				<form class="booking-form__step" data-wp-on--submit="actions.next" data-wp-bind--hidden="!context.step.isActive" data-wp-class--hidden="context.isActive">
					<template data-wp-each--field="context.step.fields" data-wp-each-key="context.field.id">
						<div class="booking-form__field">
							<label data-wp-bind--for="context.field.id" data-wp-text="context.field.label"></label>
							<input 
								data-wp-bind--type="context.field.type"
								data-wp-bind--id="context.field.id"
								data-wp-bind--value="context.field.value"`
								data-wp-bind--required="context.field.required"
								data-wp-on--input="actions.updateField"
							/>
						</div>
					</template>

					<div class="booking-form__review-step" data-wp-bind--hidden="!actions.isLastStep">
						<template data-wp-each--field="actions.reviewFields" data-wp-each-key="context.field.id">
							<div class="booking-form__review-step-field">
								<div class="booking-form__review-step-field-label" data-wp-text="context.field.label"></div>
								<div class="booking-form__review-step-field-value" data-wp-text="context.field.value"></div>
							</div>
						</template>
					</div>

					<div class="booking-form__actions">
						<div class="booking-form__actions-col">
							<button class="booking-form__button booking-form__button--secondary" type="button" data-wp-on--click="actions.back" data-wp-bind--hidden="actions.isFirstStep"><?php esc_html_e( 'Back', 'booking-form' ); ?></button>
						</div>

						<div class="booking-form__actions-col">
							<button class="booking-form__button booking-form__button--primary" data-wp-bind--hidden="actions.isLastStep"><?php esc_html_e( 'Next', 'booking-form' ); ?></button>
							<button class="booking-form__button booking-form__button--primary" data-wp-on--click="actions.submit" data-wp-bind--hidden="!actions.isLastStep" data-wp-bind--disabled="context.submitting"><?php esc_html_e( 'Submit', 'booking-form' ); ?></button>
						</div>
					</div>
				</form>
			</template>

			<div class="booking-form__submitted" data-wp-bind--hidden="!context.submitted">
				<div class="booking-form__submitted-title"><?php esc_html_e( 'Booking received! 🎉', 'booking-form' ); ?></div>
				<div class="booking-form__submitted-message"><?php esc_html_e( 'Thank you for your booking. We will be in touch shortly to confirm it.', 'booking-form' ); ?></div>
				<button class="booking-form__button booking-form__button--primary" data-wp-on--click="actions.reset"><?php esc_html_e( 'Book another!', 'booking-form' ); ?></button>
			</div>
		</div>

	</div>
</div>
