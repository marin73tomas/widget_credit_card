<?php

class CustomWidget extends WP_Widget
{

    private $urlCredit = "https://hardbacon-test.s3.amazonaws.com/test/id_credit_card.json";
    private $urlImage  = "https://hardbacon-prod.s3.us-east-1.amazonaws.com/comparators/id_credit_card_card";

    private $languages = array(
        'fr' => array(
            'name' => 'Frances',
            'value' => 'french',
            'key' => 'fr'
        ),
        'en' => array(
            'name' => 'English',
            'value' => 'english',
            'key' => 'en'
        )
    );

    function CustomWidget()
    {
        // Widget Builder.
        $widget_ops = array(
            'classname' => 'container_widget',
            'description' => "Widget credit card"
        );
        $this->WP_Widget('widget_container', "Widget Credit Card", $widget_ops);
    }

    function widget($args, $instance)
    {
        // Content of the Widget to be displayed in the Sidebar
        echo $before_widget;
        $creditCardData = self::getCreditData($instance["credit_id"]);
        $imageSrc = self::getCreditImage($instance["credit_id"]);
        ?>
        <aside id='container-widget' class='widget credit_widget'>

            <div class="wrapper-container">
                <a class="wrapper-credit__link" href="<?php echo wp_kses_post(((array) $creditCardData['link'])[$instance['credit_language']]); ?>" target="_blank" rel="noopener noreferrer">
                    <div class="wrapper-section__main">
                        <div class="wrapper__image">
                            <img src="<?php echo wp_kses_post($imageSrc); ?>" alt="Credit">
                        </div>
                        <div class="wrapper__title">
                            <div class="wrapper-institution__name">
                                <?php echo wp_kses_post(((array) $creditCardData['institution']->name)[$instance["credit_language"]]); ?>
                            </div>
                            <div class="wrapper-credit-car__name">
                                <?php echo wp_kses_post(((array) $creditCardData['name'])[$instance["credit_language"]]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="wrapper-section__insurance">
                        <div class="wrapper__insurance">
                        <?php echo wp_kses_post(((array) $creditCardData['insurance'])[$instance["credit_language"]]); ?>
                        </div>
                    </div>
                </a>
            </div>
        </aside>
        <?php   
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance["credit_widget_title"] = strip_tags($new_instance["credit_widget_title"]);
        $instance["credit_id"] = strip_tags($new_instance["credit_id"]);
        $instance["credit_language"] = strip_tags($new_instance["credit_language"]);
        
        return $instance;
    }

    function getCreditData($id){
        try {
            $url = self::getUrl(empty($id) ? '5f342a3992ec22115033b2fb' : $id);
            $data = file_get_contents($url);
            return (array) json_decode($data);
        } catch (\Throwable $th) {
            //throw $th;
            return null;
        }
    }

    function getCreditImage($id){
        return str_replace('id_credit_card', empty($id) ? '5f342a3992ec22115033b2fb' : $id, $this->urlImage);
    }

    function getUrl($idCredit){
        return str_replace('id_credit_card', $idCredit, $this->urlCredit);
    }


    function form($instance)
    {
    ?>
        <div class="container">
            <div class="wrapper-title">
                <label for="<?php echo wp_kses_post($this->get_field_id('credit_widget_title')); ?>">Widget Title</label>
                <input class="credit_widget_title" id="<?php echo wp_kses_post($this->get_field_id('credit_widget_title')); ?>" name="<?php echo wp_kses_post($this->get_field_name('credit_widget_title')); ?>" value="<?php echo wp_kses_post(esc_attr($instance["credit_widget_title"])); ?>" type="text">
            </div>
            <div class="wrapper-id">
                <label for="<?php echo wp_kses_post($this->get_field_id('credit_id')); ?>">Credit Card ID</label>
                <input class="credit_card_id" id="<?php echo wp_kses_post($this->get_field_id('credit_id')); ?>" name="<?php echo wp_kses_post($this->get_field_name('credit_id')); ?>" value="<?php echo wp_kses_post(esc_attr($instance["credit_id"])); ?>" type="text">
            </div>
            <div class="wrapper-id">
                <label for="<?php echo wp_kses_post($this->get_field_id('credit_language')); ?>">Language</label>
                <select name="<?php echo wp_kses_post($this->get_field_name('credit_language')); ?>" id="<?php echo wp_kses_post($this->get_field_id('credit_language')); ?>">
                    <?php foreach($this->languages as $language): ?> 
                        <?php 
                            if(wp_kses_post(esc_attr($instance["credit_language"])) == $language["value"]) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                        ?>
                        <option value="<?php echo wp_kses_post($language['value']); ?>" <?php echo $selected;?> ><?php echo wp_kses_post($language['name']); ?></option>
                    <?php endforeach; ?>
                </select>                
            </div>
        </div>
<?php
    }
}
