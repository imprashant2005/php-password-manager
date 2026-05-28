<?php

class PasswordGenerator {

    public function generatePassword(
        $uppercase,
        $lowercase,
        $numbers,
        $special
    ) {

        $upperChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $lowerChars = 'abcdefghijklmnopqrstuvwxyz';

        $numberChars = '0123456789';

        $specialChars = '!@#$%^&*()_+';

        $passwordArray = [];

        // Uppercase letters
        for($i = 0; $i < $uppercase; $i++){

            $passwordArray[] = $upperChars[
                rand(0, strlen($upperChars) - 1)
            ];

        }

        // Lowercase letters
        for($i = 0; $i < $lowercase; $i++){

            $passwordArray[] = $lowerChars[
                rand(0, strlen($lowerChars) - 1)
            ];

        }

        // Numbers
        for($i = 0; $i < $numbers; $i++){

            $passwordArray[] = $numberChars[
                rand(0, strlen($numberChars) - 1)
            ];

        }

        // Special characters
        for($i = 0; $i < $special; $i++){

            $passwordArray[] = $specialChars[
                rand(0, strlen($specialChars) - 1)
            ];

        }

        // Shuffle password
        shuffle($passwordArray);

        // Convert array to string
        return implode('', $passwordArray);

    }

}

?>