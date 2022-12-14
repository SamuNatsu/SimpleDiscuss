<?php
namespace SimpleDiscuss;

class WidgetException extends \Exception {}

class Widget {
    static private $_instance = [];

    // Register widget
    static public function register(string $name, string $path): void {
        if (preg_match('~^\w+$~', $name) !== 1)
            throw new WidgetException('Invalid widget name', 1);

        if (!is_file($path))
            throw new WidgetException('Invalid widget path', 2);

        if (isset(self::$_instance[$name]))
            throw new WidgetExceptoin('Widget name duplicated', 3);

        self::$_instance[$name] = $path;
    }
    
    // Auto register
    static public function autoRegister(string $path, string $prefix = ''): void {
        $widget = scandir($path);
        foreach ($widget as $item)
        if (is_file("$path/$item"))
            Widget::register($prefix . basename($item, '.php'), "$path/$item");
    }

    // Get widget
    static public function &get(string $name): Widget {
        if (!isset(self::$_instance[$name]))
            throw new WidgetException('Widget not exists', 4);

        if (is_string(self::$_instance[$name]))
            self::$_instance[$name] = new Widget($name, self::$_instance[$name]);

        return self::$_instance[$name];
    }

    private $widgetName;
    private $widgetPath;
    private $varlist = [];

    public function __construct(string $name, string $path) {
        $this->widgetName = $name;
        $this->widgetPath = $path;
    }

    public function __get(string $key) {
        return $this->varlist[$key] ?? null;
    }

    public function __set(string $key, $data) {
        $this->varlist[$key] = $data;
    }

    // Render widget
    public function render(): void {
        try {
            ob_start();
            if ((@include $this->widgetPath) === false)
                throw new WidgetException('Fail to include widget', 5);
            ob_end_flush();
        }
        catch (\Throwable $e) {
            ob_end_clean();
            throw new WidgetException('Fail to render widget', 6, $e);
        }
    }

}
