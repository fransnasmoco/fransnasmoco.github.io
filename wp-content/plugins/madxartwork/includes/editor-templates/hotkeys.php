<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-madxartwork-hotkeys">
	<# var ctrlLabel = environment.mac ? 'Cmd' : 'Ctrl'; #>
	<div id="madxartwork-hotkeys__content">
		<div id="madxartwork-hotkeys__actions" class="madxartwork-hotkeys__col">

			<div class="madxartwork-hotkeys__header">
				<h3><?php echo __( 'Actions', 'madxartwork' ); ?></h3>
			</div>
			<div class="madxartwork-hotkeys__list">
				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Undo', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Z</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Redo', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>Z</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Copy', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>C</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Paste', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>V</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Paste Style', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>V</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Delete', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>Delete</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Duplicate', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>D</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Save', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>S</span>
					</div>
				</div>

			</div>
		</div>

		<div id="madxartwork-hotkeys__navigation" class="madxartwork-hotkeys__col">

			<div class="madxartwork-hotkeys__header">
				<h3><?php echo __( 'Go To', 'madxartwork' ); ?></h3>
			</div>
			<div class="madxartwork-hotkeys__list">
				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Finder', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>E</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Show / Hide Panel', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>P</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Responsive Mode', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>M</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'History', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>H</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Navigator', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>I</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Template Library', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>L</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Keyboard Shortcuts', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>?</span>
					</div>
				</div>

				<div class="madxartwork-hotkeys__item">
					<div class="madxartwork-hotkeys__item--label"><?php echo __( 'Quit', 'madxartwork' ); ?></div>
					<div class="madxartwork-hotkeys__item--shortcut">
						<span>Esc</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
