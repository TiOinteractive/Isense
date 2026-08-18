
<?php if (!empty($data['search_engine'])): ?>
    <div class="search-engine-2">
        <?php
        $selected_dates = array();
        if (!empty($data['filters']['d'])) {
            $tmp = explode('-', $data['filters']['d']);
            if (isset($tmp[1])) {
                /* $count = 30;
                  $time = strtotime($tmp[0]);
                  $time_to = strtotime($tmp[1]);
                  while($time <= $time_to && $count) {
                  $selected_dates[] = $time;
                  $time = $time + 24 * 60 * 60;
                  $count--;
                  } */
            } elseif (isset($tmp[0])) {
                $selected_dates[] = strtotime($tmp[0]);
            }
        }
        $selected_date = !empty($data['filters']['d']) ? $data['filters']['d'] : '';
        if (!empty($data['filters']['t'])) {
            foreach ($data['types'] as $type):
                if (!empty($data['filters']) && !empty($data['filters']['t']) && $data['filters']['t'] == $type['slug']) {
                    $cat_name = !empty($type['name2']) ? $type['name2'] : $type['name'];
                }
            endforeach;
        }
        ?>
        <form action="/<?= $data['form_url']; ?>" method="get" class="events-form">
            <div class="search-top">
                <div class="filter-box filter-cat">
                    <div class="inside">
                        <div class="choose-cat"><span><?= !empty($cat_name) ? $cat_name : lang('Event.user.Categories'); ?></span> <i class="fa-solid fa-chevron-right"></i></div>
                        <div class="list trans400">
                            <div class="list-inside">  
                                <?php if (!empty($data['types'])): $cnt_all = 0; ?>
                                    <?php foreach ($data['types'] as $type): $cnt_all = $cnt_all + $type['count']; ?>
                                        <div class="type<?= !empty($data['filters']) && !empty($data['filters']['t']) && $data['filters']['t'] == $type['slug'] ? ' active' : ''; ?>" data-slug="<?= $type['slug']; ?>">
                                            <div class="name"><?= !empty($type['name2']) ? $type['name2'] : $type['name']; ?></div>
                                            <div class="count"><?php if (isset($type['count'])): ?>(<?= $type['count']; ?>)<?php endif; ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="type" data-slug="all">
                                        <div class="name">Wszystkie kategorie</div>
                                        <div class="count">(<?= $cnt_all; ?>)</div>
                                    </div>
                                <?php endif; ?>
                            </div>  
                        </div>
                    </div>		
                </div>
                <input class="field cat-type" type="hidden" name="t" value="<?= !empty($data['filters']['t']) ? $data['filters']['t'] : ''; ?>" />
                <div class="filter-box filter-date">
                    <input class="field" type="text" name="d" value="<?= !empty($data['filters']['d']) ? $data['filters']['d'] : ''; ?>" autocomplete="off" placeholder="Wybierz datę" />
                    <button><svg viewBox="0 0 32 32"><g><path d="M29.334,3H25V1c0-0.553-0.447-1-1-1s-1,0.447-1,1v2h-6V1c0-0.553-0.448-1-1-1s-1,0.447-1,1v2H9V1   c0-0.553-0.448-1-1-1S7,0.447,7,1v2H2.667C1.194,3,0,4.193,0,5.666v23.667C0,30.806,1.194,32,2.667,32h26.667   C30.807,32,32,30.806,32,29.333V5.666C32,4.193,30.807,3,29.334,3z M30,29.333C30,29.701,29.701,30,29.334,30H2.667   C2.299,30,2,29.701,2,29.333V5.666C2,5.299,2.299,5,2.667,5H7v2c0,0.553,0.448,1,1,1s1-0.447,1-1V5h6v2c0,0.553,0.448,1,1,1   s1-0.447,1-1V5h6v2c0,0.553,0.447,1,1,1s1-0.447,1-1V5h4.334C29.701,5,30,5.299,30,5.666V29.333z" fill="#333332"/><rect fill="#333332" height="3" width="4" x="7" y="12"/><rect fill="#333332" height="3" width="4" x="7" y="17"/><rect fill="#333332" height="3" width="4" x="7" y="22"/><rect fill="#333332" height="3" width="4" x="14" y="22"/><rect fill="#333332" height="3" width="4" x="14" y="17"/><rect fill="#333332" height="3" width="4" x="14" y="12"/><rect fill="#333332" height="3" width="4" x="21" y="22"/><rect fill="#333332" height="3" width="4" x="21" y="17"/><rect fill="#333332" height="3" width="4" x="21" y="12"/></g></svg></button>
                    <div class="calendar-box">
                        <input class="page-content" type="hidden" value="<?= $id_cont; ?>" />
                        <div class="event-calendar-box">
                            <?= view('Modules\Event\Views\user\calendar\_month_inside.php', array('data' => array('list' => $data['events_count']))); ?>
                        </div>
                    </div>
                </div>
                <div class="filter-box filter-search">
                    <input class="field" type="text" name="s" value="<?= !empty($data['filters']['s']) ? $data['filters']['s'] : ''; ?>" placeholder="<?= lang('Event.user.FindEvent'); ?>" autocomplete="off" />
                    <button><svg viewBox="0 0 512 512"><path d="M456.69,421.39,362.6,327.3a173.81,173.81,0,0,0,34.84-104.58C397.44,126.38,319.06,48,222.72,48S48,126.38,48,222.72s78.38,174.72,174.72,174.72A173.81,173.81,0,0,0,327.3,362.6l94.09,94.09a25,25,0,0,0,35.3-35.3ZM97.92,222.72a124.8,124.8,0,1,1,124.8,124.8A124.95,124.95,0,0,1,97.92,222.72Z"/></svg></button>
                </div>
                <div class="filter-box filter-options">
                    <input class="field" type="checkbox" name="o" value="p" id="search-option-p"<?php if (!empty($data['filters']['o']) && $data['filters']['o'] == 'p'): ?> checked="checked"<?php endif; ?> />
                    <label for="search-option-p"><?= lang('Event.user.MostPopular'); ?></label>
                </div>
                <div class="filter-box filter-view">
                    <p class="label"><?= lang('Event.user.View'); ?>:</p>
                    <input class="field" type="radio" name="v" value="" id="search-view-0"<?php if (empty($data['filters']['v'])): ?> checked="checked"<?php endif; ?> />
                    <label for="search-view-0"><svg viewBox="0 0 48 48"><path d="M8 28h8v-8h-8v8zm0 10h8v-8h-8v8zm0-20h8v-8h-8v8zm10 10h24v-8h-24v8zm0 10h24v-8h-24v8zm0-28v8h24v-8h-24z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></label>
                    <input class="field" type="radio" name="v" value="1" id="search-view-1"<?php if (!empty($data['filters']['v']) && $data['filters']['v'] == 1): ?> checked="checked"<?php endif; ?> />
                    <label for="search-view-1"><svg viewBox="0 0 48 48"><path d="M8 22h10v-12h-10v12zm0 14h10v-12h-10v12zm12 0h10v-12h-10v12zm12 0h10v-12h-10v12zm-12-14h10v-12h-10v12zm12-12v12h10v-12h-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg></label>
                </div>
            </div>
            <div class="search-carousel">
                <div class="carousel-wrap">
                    <?php foreach ($data['days'] as $day): ?>
                        <div class="day day-no-<?= $day['day_no']; ?><?= in_array($day['time'], $selected_dates) ? ' active' : '' ?><?= empty($day['count']) ? ' no-events' : '' ?>">
                            <div class="header">
                                <?php if (!empty($day['weekend_format'])): ?><span class="day-weekend<?= $selected_date == $day['weekend_format'] ? ' active' : ''; ?>" data-date="<?= $day['weekend_format']; ?>"><?= lang('Event.user.Weekend'); ?></span><?php endif; ?>
                                <span class="day-name" data-date="<?= $day['date_format']; ?>"><?= $day['day_name']; ?></span>
                            </div>
                            <div class="day-date" data-date="<?= $day['date_format']; ?>">
                                <strong><?= $day['day']; ?></strong>
                                <span><?= $day['month_name']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="clear"></div>
        </form>
    </div>
<?php endif; ?>