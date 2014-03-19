<?php
/**
 * Wire add form body
 *
 * @uses $vars["post"]
 */

elgg_load_js("elgg.thewire");

$post = elgg_extract("post", $vars);
$char_limit = thewire_tools_get_wire_length();

$text = elgg_echo("post");
if ($post) {
	$text = elgg_echo("reply");
}
$chars_left = elgg_echo("thewire:charleft");

$parent_input = "";
if ($post) {
	$parent_input = elgg_view("input/hidden", array(
		"name" => "parent_guid",
		"value" => $post->guid,
	));
}

$count_down = "<span>$char_limit</span> $chars_left";
$num_lines = 2;
if ($char_limit == 0) {
	$num_lines = 3;
	$count_down = "";
} else if ($char_limit > 140) {
	$num_lines = 3;
}

$post_input = elgg_view("input/plaintext", array(
	"name" => "body",
	"class" => "mtm",
	"id" => "thewire-textarea",
	"rows" => $num_lines,
));

$submit_button = elgg_view("input/submit", array(
	"value" => $text,
	"id" => "thewire-submit-button",
));

$access_input = "";
if (thewire_tools_groups_enabled()) {

	if ($post) {
		$access_input = elgg_view("input/hidden", array("name" => "access_id", "value" => $post->access_id));
	} else {
		$page_owner_entity = elgg_get_page_owner_entity();

		if ($page_owner_entity instanceof ElggGroup) {
			// in a group only allow sharing in the current group
			$access_input = elgg_view("input/hidden", array("name" => "access_id", "value" => $page_owner_entity->group_acl));
		} else {
			$params = array(
				"name" => "access_id"
			);
				
			if (elgg_in_context("widgets")) {
				$params["class"] = "thewire-tools-widget-access";
			}
				
			$access_input = elgg_view("input/access", $params);
		}
	}
}

echo <<<HTML
	$post_input
<div id="thewire-characters-remaining">
	$count_down
</div>
<div class="elgg-foot mts">
	$parent_input
	$submit_button
	$access_input
</div>
HTML;
