var IMPORT=false;
$(function(){
    $('.multi-select-search').on('change', function(e){
        e.preventDefault();
        ajaxCall($(this).closest('form').attr('action'), {action: 'movies', search: $(this).val()}, 'searchMovies', {target: this});
    });
    
    $('.add-cinema-hour').on('click', function(e){
        e.preventDefault();
        let h = $(this).prev().data('h');
        ajaxCall($(this).attr('href'), {h: h + 1}, 'addCinemaHour', {target: this});
    });
    
    $('.cinema-import-template, .cinema-import-date').on('change', function(e){
        e.preventDefault();
        if(!$(this).closest('form').hasClass('loading')) {
            $(this).closest('form').addClass('loading');
            $(this).closest('form').find('.import-content').html($('<span></span>').addClass('loader'));
            let file = $(this).closest('form').find('.import-file').val();
            let template = $(this).closest('form').find('.cinema-import-template').val();
            let date = $(this).closest('form').find('.cinema-import-date').val();
            ajaxCall($(this).closest('form').attr('action'), {file: file, template: template, date: date}, 'importCalendar', {target: $(this).closest('form')});
        }
    });
    
    $('form.cinema-import').on('submit', function(e){
        e.preventDefault();
        let form = this;
        let total = $(form).find('.import-content table tr.tr').length;
        let selected = 0;
        let count = 0;
        let first = null;
        $(form).find('.import-content table tr.tr').each(function() {
            if($(this).find('.import .is').prop('checked')) {
                selected++;
                if(!first) {
                    first = this;
                }
            } else if($(this).hasClass('success') || $(this).hasClass('exist')) {
                count++;
            }
        });
        if(first && selected && $(form).find('.cinema-place').val()) {
            IMPORT = false;
            let modal = $.confirm({
                title: $(form).data('title'),
                content: '<div class="repertoire-import">' + $(form).data('message') + ' <strong>' + selected + '/' + total + '</strong><div class="progress-bar"><div class="progress"></div><span>0/' + selected + '</span></div></div>',
                type: 'orange',
                boxWidth: '500px',
                useBootstrap: false,
                buttons: {
                    resume: {
                        text: $(form).data('btn-resume'),
                        btnClass: 'btn-orange',
                        isHidden: true,
                        action: function() {
                            IMPORT = false;
                            let first = null;
                            let count = 0;
                            $(form).find('.import-content table tr.tr').each(function() {
                                if($(this).find('.import .is').prop('checked') && !$(this).hasClass('error') && !$(this).hasClass('success') && !$(this).hasClass('exist')) {
                                    if(!first) {
                                        first = this;
                                    }
                                }
                                if($(this).hasClass('success') || $(this).hasClass('exist') || $(this).hasClass('error')) {
                                    count++;
                                }
                            });
                            if(first && selected && $(form).find('.cinema-place').val()) {
                                importRepertoireRow(first, this, total, selected, count);
                            }
                            this.buttons.close.hide();
                            this.buttons.resume.hide();
                            this.buttons.cancel.show();
                            return false;
                        }
                    },
                    cancel: {
                        text: $(form).data('btn-cancel'),
                        btnClass: 'btn-red',
                        action: function() {
                            IMPORT = true;
                            this.buttons.close.show();
                            this.buttons.resume.show();
                            this.buttons.cancel.hide();
                            return false;
                        }
                    },
                    close: {
                        text: $(form).data('btn-close'),
                        isHidden: true,
                        action: function() {
                            
                        }
                    },
                }
            });
            importRepertoireRow(first, modal, total, selected);
        }
    })

    $(document).on('click', '.delete-cinema-hour', function(e){
        e.preventDefault();
        $(this).closest('.time-box').remove();
    });
    $(document).on('change', '.change-all-import', function(e){
        if($(this).prop('checked')) {
            $(this).closest('.import-content').find('.import .is').prop('checked', true);
        } else {
            $(this).closest('.import-content').find('.import .is').prop('checked', false);
        }
    });
});

function searchMovies(obj, params) {
    $(params.target).siblings('.multi-select-box').html(obj.html);
}

function addCinemaHour(obj, params) {
    $(params.target).before(obj.html);
    initializeDataPicker($(params.target).prev());
}

function importCalendar(obj, params) {
    console.log(obj);
    $(params.target).find('.import-content').html(obj.html);
    initializeDataPicker($(params.target).find('.import-content'));
    setTimeout(function(){$(params.target).removeClass('loading')}, 500);
}


function importRepertoireRow(row, modal, total, selected, count=0) {
    let data = {
        place: $(row).closest('form').find('.cinema-place').val(),
        date: $(row).find('.date input').val(),
        movie: $(row).find('.movie select').val(),
        type: $(row).find('.type select').val(),
        special: $(row).find('.options .special').val(),
        surprise: $(row).find('.options .surprise').val(),
        premiere: $(row).find('.options .premiere').val(),
        pre_premiere: $(row).find('.options .pre-premiere').val(),
        title: $(row).find('.import .title').val(),
        tr: $(row).find('.import .tr').val(),
        is: $(row).find('.import .is').prop('checked'),
        total: total,
        selected: selected,
        count: count,
    };
    ajaxCall($(this).closest('form').attr('action'), data, 'saveCalendar', {target: row, modal: modal});
}

function saveCalendar(obj, params) {
    if(obj.html) {
        //$('.jconfirm .repertoire-import .response').html(obj.html);
    }
    $('.jconfirm .repertoire-import .progress-bar .progress').css('width', (obj.count * 100 / obj.selected) + '%');
    $('.jconfirm .repertoire-import .progress-bar span').text(obj.count + '/' + obj.selected);
    $(params.target).removeClass('exist success error');
    if(obj.result) {
        $(params.target).addClass(obj.exist ? 'exist' : 'success');
        $(params.target).find('.import .is').prop('checked', false);
    } else {
        $(params.target).addClass('error');
    }
    if(obj.selected==obj.count) {
        params.modal.buttons.resume.hide();
        params.modal.buttons.cancel.hide();
        params.modal.buttons.close.show();
    }
    if($(params.target).next('.tr').length && obj.selected>=obj.count && !IMPORT) {
        importRepertoireRow($(params.target).next('.tr'), params.modal, obj.total, obj.selected, obj.count);
    }
}