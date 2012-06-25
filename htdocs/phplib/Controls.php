<?php

class Controls {
    /**
     * @param string $time
     * @param array $times
     * @param int $until_time
     * @return string
     */
    public static function buildTimeControl($time, $times, $until_time = null) {
        if (empty($until_time)) {
            $from_times = $times;
        } else {
            $from_times = array();
            foreach (array_reverse(array_keys($times)) as $key) {
                if ($key == $until_time) {
                    break;
                }

                $from_times[$key] = $times[$key];
            }

            // Reset times back in least to greatest
            $from_times = array_reverse($from_times);
        }

        return self::buildControl(self::buildSelect('time', 'Time', $from_times, $time));
    }

    /**
     * @param int $from_until
     * @param int $until_time
     * @param array $times
     * @return string
     */
    public static function buildUntilControl($from_until, $until_time, $times) {
        // Add in an "empty" value for clearing until
        $until_times = array('' => 'Now');

        // Building an array of until values we can use that are valid
        // e.g. from the the start time ($from_until) until now
        foreach ($times as $key => $val) {
            if ($key == $from_until) {
                break;
            }

            $until_times[$key] = $val;
        }

        return self::buildControl(self::buildSelect('until', 'Until', $until_times, $until_time));
    }

    /**
     * @param bool $hide_deploys
     * @return string
     */
    public static function buildShowDeploysControl($hide_deploys, $itemize=true) {
        $show_deploys = !$hide_deploys;

        $html = "<span style='margin-left: 5px;'>" . self::buildControl(self::buildCheckbox('hide_deploys', 'Hide All Deploys', $hide_deploys));

        if ($itemize && $show_deploys) {
            $deploy_html = "Deploys hidden: ";
            foreach (DeployConstants::$deploys as $deploy_name => $deploy) {
                $checked = true;
                if (!GraphFactory::isHiddenDeployType($deploy_name)) {
                    $checked = false;
                }
                $deploy_html .= "<span style=\"color: $deploy[color]; margin-right: 5px;\">" . self::buildCheckbox($deploy_name, $deploy['title'], $checked) . "</span>\n";
            }
            $html .= self::buildControl($deploy_html);
        }

        $html .= "<span style='color: #dddddd'>Historical Average</span>";
        $html .= "</span>";

        return $html;
    }

    /**
     * @param string $average
     * @return string
     */
    public static function buildAverageControl($average) {
        $averages = array(
            'mean_90' => 'Mean',
            'upper_90' => 'Upper 90',
            'upper' => 'Upper',
        );
        return self::buildControl(self::buildRadio('average', 'Average', $averages, $average));
    }

    /**
     * @param string $page
     * @param array $all_methods
     * @return string
     */
    public static function buildPageControl($page, $all_methods) {
        sort($all_methods);

        $all_pages = array(
            '' => 'All'
        );

        foreach ($all_methods as $method) {
            $all_pages[$method] = str_replace('.', '/', $method) . '.php';
        }

        return self::buildControl(self::buildSelect('page', 'Page', $all_pages, $page));
    }

    /**
     * @param string $name
     * @param string $title
     * @param array $values
     * @param string $selected_value
     * @return string
     */
    public static function buildRadio($name, $title, $values, $selected_value) {
        $html = '';

        if ($title) {
            $html .= "<label>$title:</label>";
        }

        foreach ($values as $key => $value) {
            $html .= "<input name='$name' type='radio' value='$key'";

            if ($key == $selected_value) {
                $html .= " checked=checked";
            }

            $html .= "/><span>$value</span>";
        }

        return $html;
    }

    /**
     * @param string $name
     * @param string $title
     * @param bool $checked
     * @return string
     */
    public static function buildCheckbox($name, $title, $checked) {
        $html = "<input type='checkbox' name='$name' value='true'";

        if ($checked) {
            $html .= ' checked=checked';
        }

        $html .= "/>";

        if ($title) {
            $html .= "<label>$title</label>";
        }

        return $html;
    }

    /**
     * @param string $name
     * @param string $title
     * @param array $values
     * @param string $selected_value
     * @return string
     */
    public static function buildSelect($name, $title, $values, $selected_value) {
        $html = '';

        if ($title) {
            $html .= "<label>$title:</label>";
        }

        $html .= "<select name='$name'>";

        foreach ($values as $key => $value) {
            $html .= "<option value='$key'";

            if ($key == $selected_value) {
                $html .= " selected=selected";
            }

            $html .= ">$value</option>";
        }

        $html .= "</select>";

        return $html;
    }

    /**
     * @param string $html
     * @return string
     */
    private static function buildControl($html) {
        return "<span class='control'>$html</span>";
    }
}
