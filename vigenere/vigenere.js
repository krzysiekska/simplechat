/**
  *  vigenere.js - JavaScript Object for Vigenere ciphers
  *  @type {object}
  *  @namespace
  */

function Vigenere() {

    "use strict";

    /**
     * plaintext - the real, original message
     * @type {String}
     */
    var plaintext = "";

    /**
     * ciphertext - the encrypted version of the message
     * @type {String}
     */
    var ciphertext = "";

    /**
     * keyword - the password used to encrypt and decrypt the ciphertext
     * @type {String}
     */
    var keyword = "";

    /**
     * alphabets - a slightly modified version of the Vigenere table,
     * @type {Array}
     */
    var alphabets = [];

    /**
     *  init - create our plaintext alphabet and Vigenere table
     */
    var init = function init() {

        var x;

        // populate the first row of our Vigenere table
        alphabets[0] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        // populate the rest of our Vigenere table
        for (x = 1; x < alphabets[0].length; x = x + 1) {
            alphabets[x] = alphabets[0].substr(x);
            alphabets[x] = alphabets[x].concat(alphabets[0].substring(0, x));
        }
    };

    /**
     *  buildKeyword - convert user-provided password to ALL CAPS ALPHAs only
     *  @param {String} password, the user-provided password to reduce
     */
    var buildKeyword = function buildKeyword(password) {

        password = password.match(/[A-Za-z]/g);
        password = password.toString();
        password = password.replace(/[,]/g, "");

        keyword = password.toUpperCase();
    };

    /**
     *  encrypt - creates ciphertext using the Vigenere algorithm
     *  @param {String} plaintext, the message to convert into ciphertext
     *  @param {String} password, the key to use in the Vigenere cipher
     */
    this.encrypt = function encrypt(plaintext, password) {

        var x, pwIndex, vRow, thisLetter, thisRow;

        // initialize defaults
        //this.keyword = password.toUpperCase();
        buildKeyword(password);
        plaintext = plaintext.toUpperCase();
        ciphertext = "";

        // we'll need to keep track of which letter of the password we're currently using
        pwIndex = 0;

        // time to traverse the plaintext message
        for (x = 0; x < plaintext.length; x = x + 1) {
            // first figure out which row of the Vigenere table we must use
            vRow = alphabets[0].indexOf(keyword[pwIndex]);
            thisLetter = alphabets[0].indexOf(plaintext[x]);

            // if the next char of plaintext is NOT a capital letter
            if (thisLetter === -1) {
                // then just pass it through unchanged
                ciphertext += plaintext[x];
            } else {
                // otherwise find its counterpart in the V. table
                thisRow = alphabets[vRow];
                ciphertext += thisRow[thisLetter];
                // don't forget to move to next letter in our password
                pwIndex = pwIndex + 1;
            }

            // if we "run out of password", start back at 1st letter again
            if (pwIndex >= keyword.length) {
                pwIndex = 0;
            }
        }

        // now we're ready to share the encrypted result
        return ciphertext;
    };

    /**
     *  decrypt - creates plaintext using the Vigenere algorithm
     *  @param {String} ciphertext, the message to be deciphered
     *  @param {String} password, the Vigenere key to use in the deciphering process.
     */
    this.decrypt = function decrypt(ciphertext, password) {

        // initialize defaults
        // this.keyword = password.toUpperCase();
        buildKeyword(password);
        plaintext = "";
        ciphertext = ciphertext.toUpperCase();

        var pwIndex, x, vRow, thisLetter, thisRow;

        // we'll need to keep track of which letter of the password we're currently using
        pwIndex = 0;

        // let's traverse the ciphertext message
        for (x = 0; x < ciphertext.length; x = x + 1) {

            // figure out which row of the Vigenere table should have been used with this password
            vRow = alphabets[0].indexOf(keyword[pwIndex]);
            thisLetter = alphabets[vRow].indexOf(ciphertext[x]);

            // if the next char of ciphertext is NOT a capital letter
            if (thisLetter === -1) {
                // then just pass it through unchanged
                plaintext += ciphertext[x];
            } else {
                // otherwise find its counterpart "backwards" in the V. table
                thisRow = alphabets[0]; // this will always be the first row
                plaintext += thisRow[thisLetter];
                // don't forget to move to next letter in our password
                pwIndex = pwIndex + 1;
            }

            // if we "run out of password", start back at 1st letter again
            if (pwIndex >= keyword.length) {
                pwIndex = 0;
            }
        }

        // now we're ready to share the decrypted result
        return plaintext;
    };

    init(); // build out our alphabets grid
    return this;
}
