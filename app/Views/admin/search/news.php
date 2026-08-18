<html>
    <head>
        <style>
            .container {padding: 0 15px; margin: 0 auto; width: 1230px; max-width: 100%;}
            .search-form-box form {display: block;}
            .search-form-box form label {display: block;}
            .search-form-box form textarea {display: block; width: 300px; resize: vertical; min-height: 100px;}
            .search-results table {width: 100%; border-collapse: collapse;}
            .search-results table th {background: #1a1d21; padding: 5px; color: #fff; font-weight: 700; border: 1px solid #fff;}
            .search-results table td {border-bottom: 1px solid #eee; padding: 5px;}
            .search-results table .name {text-align: left;}
            .search-results table .date, .search-results table .link, .search-results table .edit {text-align: center;}
            .search-results table td a {text-transform: none; text-decoration: none; color: #000; font-weight: 700;}
            .search-results table td a:hover {color: #f8941f;}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="search-form-box">
                <form method="get">
                    <label>Frazy do wyszukania (można podać kilka w osobnych wierszach):</label>
                    <textarea name="search"><?=!empty($filters['search']) ? implode(PHP_EOL, $filters['search']) : ''; ?></textarea>
                    <input type="submit" name="search_btn" value="Szukaj" />
                </form>
            </div>
        </div>
        <?php if(!empty($news)): ?>
            <div class="search-results">
                <div class="container">
                    <p>Znaleziono <b><?=$count; ?></b> wyników</p>
                    <table>
                        <tr>
                            <th class="name">Nazwa</th>
                            <th class="date">Data</th>
                            <th class="link">Link</th>
                            <th class="edit">Edycja</th>
                        </tr>
                        <?php foreach($news as $n): ?>
                            <tr>
                                <td class="name"><?=$n['title']; ?></td>
                                <td class="date"><?=date('d.m.Y', strtotime($n['date'])); ?></td>
                                <td class="link"><a href="/<?=$n['link']; ?>" title="<?=esc($n['title']); ?>" target="_blank">Zobacz</a></td>
                                <td class="edit"><a href="/tiocms/news/edit/<?=$n['id_page_cont']; ?>/<?=$n['id']; ?>" title="Edytuj" target="_blank">Edytuj</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </body>
</html>