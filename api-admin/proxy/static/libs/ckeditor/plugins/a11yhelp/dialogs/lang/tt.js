﻿/*
 Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
*/
CKEDITOR.plugins.setLang("a11yhelp", "tt", {
    title: "Accessibility Instructions",
    contents: "Help Contents. To close this dialog press ESC.",
    legend: [{
        name: "Гомуми",
        items: [{
            name: "Editor Toolbar",
            legend: "Press ${toolbarFocus} to navigate to the toolbar. Move to the next and previous toolbar group with TAB and SHIFT+TAB. Move to the next and previous toolbar button with RIGHT ARROW or LEFT ARROW. Press SPACE or ENTER to activate the toolbar button."
        }, {
            name: "Editor Dialog",
            legend: "Inside a dialog, press TAB to navigate to the next dialog element, press SHIFT+TAB to move to the previous dialog element, press ENTER to submit the dialog, press ESC to cancel the dialog. When a dialog has multiple tabs, the tab list can be reached either with ALT+F10 or with TAB as part of the dialog tabbing order. With tab list focused, move to the next and previous tab with RIGHT and LEFT ARROW, respectively."
        },
            {
                name: "Editor Context Menu",
                legend: "Press ${contextMenu} or APPLICATION KEY to open context-menu. Then move to next menu option with TAB or DOWN ARROW. Move to previous option with SHIFT+TAB or UP ARROW. Press SPACE or ENTER to select the menu option. Open sub-menu of current option with SPACE or ENTER or RIGHT ARROW. Go back to parent menu item with ESC or LEFT ARROW. Close context menu with ESC."
            }, {
                name: "Editor List Box",
                legend: "Inside a list-box, move to next list item with TAB OR DOWN ARROW. Move to previous list item with SHIFT+TAB or UP ARROW. Press SPACE or ENTER to select the list option. Press ESC to close the list-box."
            },
            {
                name: "Editor Element Path Bar",
                legend: "Press ${elementsPathFocus} to navigate to the elements path bar. Move to next element button with TAB or RIGHT ARROW. Move to previous button with SHIFT+TAB or LEFT ARROW. Press SPACE or ENTER to select the element in editor."
            }]
    }, {
        name: "Командалар",
        items: [{name: "Кайтару", legend: "${undo} басыгыз"}, {
            name: "Кабатлау",
            legend: "${redo} басыгыз"
        }, {name: "Калын", legend: "${bold} басыгыз"}, {
            name: "Курсив",
            legend: "${italic} басыгыз"
        }, {name: "Астына сызылган", legend: "${underline} басыгыз"},
            {name: "Сылталама", legend: "${link} басыгыз"}, {
                name: " Toolbar Collapse command",
                legend: "${toolbarCollapse} басыгыз"
            }, {
                name: " Access previous focus space command",
                legend: "Press ${accessPreviousSpace} to access the closest unreachable focus space before the caret, for example: two adjacent HR elements. Repeat the key combination to reach distant focus spaces."
            }, {
                name: " Access next focus space command",
                legend: "Press ${accessNextSpace} to access the closest unreachable focus space after the caret, for example: two adjacent HR elements. Repeat the key combination to reach distant focus spaces."
            },
            {name: " Accessibility Help", legend: "${a11yHelp} басыгыз"}]
    }],
    tab: "Tab",
    pause: "Тыныш",
    capslock: "Caps Lock",
    escape: "Escape",
    pageUp: "Page Up",
    pageDown: "Page Down",
    leftArrow: "Сул якка ук",
    upArrow: "Өскә таба ук",
    rightArrow: "Уң якка ук",
    downArrow: "Аска таба ук",
    insert: "Өстәү",
    leftWindowKey: "Сул Windows төймəсе",
    rightWindowKey: "Уң Windows төймəсе",
    selectKey: "Select төймəсе",
    numpad0: "Numpad 0",
    numpad1: "Numpad 1",
    numpad2: "Numpad 2",
    numpad3: "Numpad 3",
    numpad4: "Numpad 4",
    numpad5: "Numpad 5",
    numpad6: "Numpad 6",
    numpad7: "Numpad 7",
    numpad8: "Numpad 8",
    numpad9: "Numpad 9",
    multiply: "Тапкырлау",
    add: "Кушу",
    subtract: "Алу",
    decimalPoint: "Унарлы нокта",
    divide: "Бүлү",
    f1: "F1",
    f2: "F2",
    f3: "F3",
    f4: "F4",
    f5: "F5",
    f6: "F6",
    f7: "F7",
    f8: "F8",
    f9: "F9",
    f10: "F10",
    f11: "F11",
    f12: "F12",
    numLock: "Num Lock",
    scrollLock: "Scroll Lock",
    semiColon: "Нокталы өтер",
    equalSign: "Тигезлек билгесе",
    comma: "Өтер",
    dash: "Сызык",
    period: "Дәрәҗә",
    forwardSlash: "Кыек сызык",
    graveAccent: "Гравис",
    openBracket: "Җәя ачу",
    backSlash: "Кире кыек сызык",
    closeBracket: "Җәя ябу",
    singleQuote: "Бер иңле куштырнаклар"
});