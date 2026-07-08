<?php
class App {
    private static $settings = [];
    private static $loaded = false;

    public static function loadSettings() {
        if (self::$loaded) return;
        try {
            $db = Database::getInstance();
            $result = $db->query("SELECT `key`, `value` FROM settings");
            while ($row = $result->fetch_assoc()) {
                self::$settings[$row['key']] = $row['value'];
            }
            self::$loaded = true;
        } catch (Exception $e) {
            self::$loaded = true;
        }
    }

    public static function getSetting($key, $default = '') {
        self::loadSettings();
        return self::$settings[$key] ?? $default;
    }

    public static function getSettings($group = null) {
        self::loadSettings();
        if ($group) {
            try {
                $db = Database::getInstance();
                $result = $db->query("SELECT `key`, `value` FROM settings WHERE group_name = '" . $db->escape($group) . "'");
                $settings = [];
                while ($row = $result->fetch_assoc()) {
                    $settings[$row['key']] = $row['value'];
                }
                return $settings;
            } catch (Exception $e) {
                return [];
            }
        }
        return self::$settings;
    }

    public static function updateSetting($key, $value) {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?");
            $stmt->bind_param("sss", $key, $value, $value);
            $stmt->execute();
            self::$settings[$key] = $value;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function formatPrice($amount) {
        $symbol = self::getSetting('shop_currency_symbol', 'KSh');
        return $symbol . ' ' . number_format((float)$amount, 2);
    }
}
