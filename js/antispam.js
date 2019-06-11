$(function () {
    $(".antispam").each(function (index) {
        let mail = $(this).text();
        let splitted = mail.split("(at)");
        mail = splitted[0] + "@" + splitted[1];
        $(this).html('<a href="mailto:' + mail + '">' + mail + '</a>');
    });
});