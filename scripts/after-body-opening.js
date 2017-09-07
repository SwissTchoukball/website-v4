function slugify(text)
{
    'use strict';
    return text.toString().toLowerCase()
        .replace(/[����]/g, 'a')        // Replace accented a with a
        .replace(/[����]/g, 'e')        // Replace accented e with e
        .replace(/[����]/g, 'i')        // Replace accented i with i
        .replace(/[����]/g, 'o')        // Replace accented o with o
        .replace(/[����]/g, 'u')        // Replace accented u with u
        .replace(/[�]/g, 'c')           // Replace � with c
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
}