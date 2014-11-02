<?php
namespace Drupal\wb_breadcrumbs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigForm extends  ConfigFormBase {
    public function getFormId() {
        return 'wb_breadcrumbs.config';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('wb_breadcrumbs.settings');

        $form['breadcrumb_menu'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Menu for breadcrumb'),
            '#default_value' => $config->get('breadcrumb_menu'),
            '#maxlength' => '255',
            '#description' => $this->t('Menu machine name from whom going to be created breadcrumb'),
        ];
        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        parent::submitForm($form, $form_state);
        $config = $this->config('wb_breadcrumbs.settings');
        $config->set('breadcrumb_menu', $form_state->getValue('breadcrumb_menu'));
        $config->save();
    }
}