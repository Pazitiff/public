<?php

function youtheme_radio($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'radio';
  element_set_attributes($element, array(
    'id',
    'name',
    '#return_value' => 'value'
  ));

  if (isset($element['#return_value']) && $element['#value'] !== FALSE && $element['#value'] == $element['#return_value']) {
    $element['#attributes']['checked'] = 'checked';
  }
  _form_set_class($element, array('form-radio', 'mdl-radio__button'));

  return '<input' . drupal_attributes($element['#attributes']) . ' /><span class="mdl-radio__label">' . $element['#title'] . '</span>';
}


function youtheme_checkbox($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'checkbox';
  element_set_attributes($element, array(
    'id',
    'name',
    '#return_value' => 'value'
  ));

  // Unchecked checkbox has #value of integer 0.
  if (!empty($element['#checked'])) {
    $element['#attributes']['checked'] = 'checked';
  }
  _form_set_class($element, array('form-checkbox', 'mdl-checkbox__input'));

  return '<input' . drupal_attributes($element['#attributes']) . ' /><span class="mdl-checkbox__label">' . $element['#title'] . '</span>';
}

function youtheme_form_element($variables) {

  $element = &$variables['element'];

  if ($element['#type'] == 'checkbox') {

    if (!empty($element['#id'])) {
      $attributes['for'] = $element['#id'];
    }

    $attributes['class'] = array(
      'mdl-checkbox',
      'mdl-js-checkbox',
      'mdl-js-ripple-effect'
    );

    return '<label' . drupal_attributes($attributes) . '>' . $element['#children'] . '</label>';
  }

  if ($element['#type'] == 'radio') {

    if (!empty($element['#id'])) {
      $attributes['for'] = $element['#id'];
    }

    $attributes['class'] = array(
      'mdl-radio',
      'mdl-js-radio',
      'mdl-js-ripple-effect'
    );

    return '<label' . drupal_attributes($attributes) . '>' . $element['#children'] . '</label>';

  }

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(
        ' ' => '-',
        '_' => '-',
        '[' => '-',
        ']' => ''
      ));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}