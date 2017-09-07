function slugify(text)
{
    'use strict';
    return text.toString().toLowerCase()
        .replace(/[באהג]/g, 'a')        // Replace accented a with a
        .replace(/[יטכך]/g, 'e')        // Replace accented e with e
        .replace(/[םלןמ]/g, 'i')        // Replace accented i with i
        .replace(/[ףעצפ]/g, 'o')        // Replace accented o with o
        .replace(/[תשüû]/g, 'u')        // Replace accented u with u
        .replace(/[ח]/g, 'c')           // Replace ח with c
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
}