<?php
$a = preg_replace_callback(
    '/./',
    function ($matches) use ($a) {
        switch ("a")
        {
            case "a":
                return;
            default:
                return preg_replace_callback(
                    '/./',
                    function ($matches) use ($a) {
                        switch ("a") // HERE
                {
                            case "a":
                                return;
                            default:
                                return;
                        }

                        return;
                    }, "..."
                );
        }

        return;
    }, "..."
);
