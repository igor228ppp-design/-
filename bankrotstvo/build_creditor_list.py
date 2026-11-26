from docx import Document
from docx.shared import Pt, Cm, Inches
from docx.enum.style import WD_STYLE_TYPE
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml.ns import qn, nsdecls
from docx.oxml import OxmlElement

doc = Document()
section = doc.sections[0]
section.page_width = Cm(21)
section.page_height = Cm(29.7)
section.left_margin = Cm(1.5)
section.right_margin = Cm(1.5)
section.top_margin = Cm(1.5)
section.bottom_margin = Cm(1.5)

style = doc.styles['Normal']
style.font.name = 'Times New Roman'
style._element.rPr.rFonts.set(qn('w:eastAsia'), 'Times New Roman')
style.font.size = Pt(10)
pf = style.paragraph_format
pf.space_after = Pt(2)
pf.space_before = Pt(0)

def set_cell_text(cell, text, size=9, bold=False, align=WD_ALIGN_PARAGRAPH.LEFT, italic=False):
    cell.text = ''
    p = cell.paragraphs[0]
    p.alignment = align
    run = p.add_run(str(text) if text is not None else '')
    run.bold = bold
    run.italic = italic
    run.font.size = Pt(size)
    run.font.name = 'Times New Roman'
    run._element.rPr.rFonts.set(qn('w:eastAsia'), 'Times New Roman')

def set_cell_shading(cell, color_hex):
    shading = OxmlElement('w:shd')
    shading.set(qn('w:fill'), color_hex)
    cell._tc.get_or_add_tcPr().append(shading)

def add_para(text='', size=10, bold=False, align=WD_ALIGN_PARAGRAPH.LEFT, space_before=0, space_after=2, italic=False):
    p = doc.add_paragraph()
    p.alignment = align
    p.paragraph_format.space_before = Pt(space_before)
    p.paragraph_format.space_after = Pt(space_after)
    run = p.add_run(text)
    run.bold = bold
    run.italic = italic
    run.font.size = Pt(size)
    run.font.name = 'Times New Roman'
    run._element.rPr.rFonts.set(qn('w:eastAsia'), 'Times New Roman')
    return p

def style_table(table):
    table.autofit = True
    tbl = table._tbl
    borders = OxmlElement('w:tblBorders')
    for name in ['top','left','bottom','right','insideH','insideV']:
        b = OxmlElement(f'w:{name}')
        b.set(qn('w:val'), 'single')
        b.set(qn('w:sz'), '4')
        b.set(qn('w:space'), '0')
        b.set(qn('w:color'), '000000')
        borders.append(b)
    tbl.tblPr.append(borders)

# Header
p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
p.paragraph_format.space_after = Pt(0)
run = p.add_run('Приложение № 1\nк приказу Минэкономразвития России\nот 05.08.2015 № 530')
run.font.size = Pt(9)
run.font.name = 'Times New Roman'
run._element.rPr.rFonts.set(qn('w:eastAsia'), 'Times New Roman')

add_para('ФОРМА', size=12, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=6, space_after=0)
add_para('Список кредиторов и должников гражданина', size=12, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_after=8)

add_para('Информация о гражданине', size=10, bold=True, space_after=4)

# Personal info table
info = [
    ('фамилия', 'Сурнаев', 'обязательно'),
    ('имя', 'Никита', 'обязательно'),
    ('отчество при наличии', 'Дмитриевич', ''),
    ('в случае изменения фамилии,\nимени, отчества указать прежние\nфамилии, имена, отчества', '', 'обязательно'),
    ('дата рождения', '25.08.2004', 'обязательно'),
    ('место рождения', 'г. Ульяновск', 'обязательно'),
    ('СНИЛС', '166-109-704 65', 'обязательно'),
    ('ИНН при наличии', '732717692942', ''),
]
t = doc.add_table(rows=len(info), cols=3)
style_table(t)
t.columns[0].width = Cm(7)
t.columns[1].width = Cm(7)
t.columns[2].width = Cm(3.5)
for i,(label,val,note) in enumerate(info):
    set_cell_text(t.rows[i].cells[0], label, size=9)
    set_cell_text(t.rows[i].cells[1], val, size=9)
    set_cell_text(t.rows[i].cells[2], note, size=8, italic=False)

add_para('документ, удостоверяющий личность', size=10, bold=True, space_before=6, space_after=2)
t2 = doc.add_table(rows=2, cols=3)
style_table(t2)
set_cell_text(t2.rows[0].cells[0], 'вид документа', 9)
set_cell_text(t2.rows[0].cells[1], 'Паспорт гражданина Российской Федерации', 9)
set_cell_text(t2.rows[0].cells[2], 'обязательно', 8)
set_cell_text(t2.rows[1].cells[0], 'серия (при наличии) и номер', 9)
set_cell_text(t2.rows[1].cells[1], '7324 615401', 9)
set_cell_text(t2.rows[1].cells[2], 'обязательно', 8)

add_para('адрес регистрации по месту жительства в Российской Федерации *', size=10, bold=True, space_before=6, space_after=2)
addr = [
    ('субъект Российской Федерации', 'Ульяновская область', 'обязательно'),
    ('район при наличии', '', ''),
    ('город при наличии', 'г. Ульяновск', ''),
    ('населенный пункт (село, поселок\nи так далее) при наличии', '', ''),
    ('улица (проспект, переулок\nи так далее) при наличии', 'ул. Отрадная', ''),
    ('номер дома (владения) при наличии', '74', ''),
    ('номер корпуса (строения) при наличии', '', ''),
    ('номер квартиры (офиса) при наличии', '9', ''),
]
t3 = doc.add_table(rows=len(addr), cols=3)
style_table(t3)
for i,(label,val,note) in enumerate(addr):
    set_cell_text(t3.rows[i].cells[0], label, size=9)
    set_cell_text(t3.rows[i].cells[1], val, size=9)
    set_cell_text(t3.rows[i].cells[2], note, size=8)

add_para('* При отсутствии регистрации по месту жительства в пределах Российской Федерации указать наименование субъекта Российской Федерации по месту пребывания без указания конкретного адреса:', size=8, space_before=2, space_after=10)

# Section I
add_para('I. Сведения о кредиторах гражданина', size=10, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=6, space_after=0)
add_para('(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,\nза исключением возникших в результате осуществления гражданином\nпредпринимательской деятельности)', size=9, align=WD_ALIGN_PARAGRAPH.CENTER, space_after=6)

add_para('1 Денежные обязательства', size=10, bold=True, space_after=2)

# Table for monetary obligations
# Columns: №, Содержание, Кредитор, Место нахождения, Основание, Сумма всего, в т.ч. задолженность, Штрафы пени и иные санкции
headers1 = ['№ п/п', 'Содержание\nобязательства', 'Кредитор', 'Место\nнахождения\n(место\nжительства)\nкредитора', 'Основание\nвозникно-\nвения', 'Сумма обязательства\nвсего', 'Сумма обязательства\nв том числе\nзадолжен-\nность', 'Штрафы,\nпени\nи иные\nсанкции']
rows1 = [
    ['1.1', 'Кредит (денежное обязательство по договору займа/кредита)',
     'АКЦИОНЕРНОЕ ОБЩЕСТВО "АЛЬФА-БАНК"',
     '107078, г. Москва, ул. Каланчевская, д. 27',
     'Договор № F0COM620250519014418 от 19.05.2025; Исп. надпись нотариуса № У-0005719347 от 18.05.2026 (нот. Чекалина Е.В.); ИП № 540858/26/73043-ИП от 20.05.2026 (ОСП № 1 по Засвияжскому р-ну г. Ульяновска)',
     '100 968,54 руб.\\n(осн. долг 79 696,73;\\nпроценты 19 601,81;\\nрасходы по исп. надписи 1 670,00)',
     '100 968,54 руб.', '—'],
    ['1.2', 'Ущерб в порядке суброгации (денежное обязательство)',
     'ПАО "Группа Ренессанс Страхование", ИНН 7725497022',
     'г. Москва, вн. тер. г. муниципальный округ Крылатское, ул. Крылатская, д. 17, корп. 1',
     'Решение Засвияжского районного суда г. Ульяновска по делу № 2-1759/2026, исп. лист № 73RS0002#2-1759/2026#1 от 07.07.2026; ИП № 5673420/26/73043-ИП от 08.07.2026 (ОСП № 1 по Засвияжскому р-ну г. Ульяновска)',
     '276 709,87 руб.\\n(ущерб 267 679,87;\\nгоспошлина 9 030,00);\\nпроценты по ст. 395 ГК РФ начисляются — сумма на дату составления списка подлежит уточнению',
     '276 709,87 руб.\\n(без учета процентов по ст. 395 ГК РФ)', '—'],
    ['1.3', 'Задолженность по кредитным платежам',
     'АО "ТБанк"',
     '127287, Россия, г. Москва, ул. Хуторская 2-я, д. 38А, стр. 26',
     'Судебный приказ № 2-1142/2026 от 03.07.2026, Судебный участок № 6 Засвияжского района г. Ульяновска; ИП № 5729875/26/73043-ИП от 09.09.2026 (ОСП № 1 по Засвияжскому р-ну г. Ульяновска)',
     '61 026,53 руб.',
     '61 026,53 руб.', '—'],
    ['1.4', 'Задолженность по кредитной карте (Кредитная СберКарта ****2137)',
     'ПАО Сбербанк',
     '117312, г. Москва, ул. Вавилова, д. 19',
     'Договор № 99TKPR25020602549676 от 06.02.2025; справка о задолженности от 24.11.2026 (СберБанк Онлайн)',
     '83 098,12 руб.\\n(срочная 42 856,50;\\nпросроченная/штрафы/пени/неустойки 40 241,62 — разбивка по справке не предоставлена)',
     '83 098,12 руб.', '40 241,62 руб.\\n(просроченная/штрафы/пени/неустойки по справке Сбербанка; разбивка не предоставлена)'],
    ['1.5', 'Задолженность по кредитному договору (Супер Сплит / Улучшенный Сплит)',
     'АО "Яндекс Банк" (лиц. Банка России № 3027)',
     '115035, г. Москва, ул. Садовническая, д. 82, стр. 2',
     'Кредитный договор № КС20250121768347299190 от 21.01.2025; справка о задолженности исх. № 202611249899214 от 24.11.2026',
     '64 760,98 руб.\\n(просроч. осн. долг 39 913,67;\\nпросроч. проценты 9 107,01;\\nнеустойка 15 740,30)',
     '64 760,98 руб.', '15 740,30 руб.\\n(неустойка)'],
]
tab1 = doc.add_table(rows=1+len(rows1), cols=8)
style_table(tab1)
for j,h in enumerate(headers1):
    set_cell_text(tab1.rows[0].cells[j], h, size=7, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
for i,row in enumerate(rows1, start=1):
    for j,val in enumerate(row):
        set_cell_text(tab1.rows[i].cells[j], val, size=7, align=WD_ALIGN_PARAGRAPH.LEFT if j>0 else WD_ALIGN_PARAGRAPH.CENTER)

add_para('2 Обязательные платежи', size=10, bold=True, space_before=6, space_after=2)
tab2 = doc.add_table(rows=7, cols=4)
style_table(tab2)
h2 = ['№ п/п', 'Наименование налога, сбора или иного\nобязательного платежа', 'Недоимка', 'Штрафы, пени и иные санкции']
for j,h in enumerate(h2):
    set_cell_text(tab2.rows[0].cells[j], h, size=8, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
tax_rows = [
    ('2.1', 'Налог на имущество физических лиц', '322,00 руб.', '—'),
    ('2.2', 'Транспортный налог (2024)', '166,09 руб.', '98,96 руб. (пени по строкам 2.2–2.3 совокупно по данным ФНС; разбивка по налогам не предоставлена)'),
    ('2.3', 'Налог на имущество физических лиц (2024)', '86,91 руб.', '0,00 руб. (пени учтены в строке 2.2)'),
    ('2.4', 'Исполнительский сбор (ИП № 540858/26/73043-ИП от 20.05.2026, ОСП № 1 по Засвияжскому р-ну г. Ульяновска УФССП по Ульяновской обл.)', '12 116,22 руб.', '—'),
    ('2.5', 'Исполнительский сбор (ИП № 5673420/26/73043-ИП от 08.07.2026, ОСП № 1 по Засвияжскому р-ну г. Ульяновска УФССП по Ульяновской обл.)', '33 205,18 руб.', '—'),
    ('2.6', 'Исполнительский сбор (ИП № 5729875/26/73043-ИП от 09.09.2026, ОСП № 1 по Засвияжскому р-ну г. Ульяновска УФССП по Ульяновской обл.)', '7 317,90 руб.', '—'),
]
for i,(n,name,ned,sanc) in enumerate(tax_rows, start=1):
    set_cell_text(tab2.rows[i].cells[0], n, size=8, align=WD_ALIGN_PARAGRAPH.CENTER)
    set_cell_text(tab2.rows[i].cells[1], name, size=8)
    set_cell_text(tab2.rows[i].cells[2], ned, size=8)
    set_cell_text(tab2.rows[i].cells[3], sanc, size=8)

add_para('Сведения о неденежных обязательствах гражданина, за исключением возникших в результате осуществления гражданином предпринимательской деятельности (в том числе о передаче имущества в собственность, выполнении работ и оказании услуг и так далее):', size=8, space_before=6, space_after=2)
add_para('_' * 95, size=9)
add_para('_' * 95, size=9)
add_para('(отсутствуют)', size=9, space_after=8)

# Sections II, III, IV - empty templates
def add_empty_monetary_section(title, subtitle, note_prefix):
    add_para(title, size=10, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=10, space_after=0)
    add_para(subtitle, size=9, align=WD_ALIGN_PARAGRAPH.CENTER, space_after=6)
    add_para('1 Денежные обязательства', size=10, bold=True, space_after=2)
    t = doc.add_table(rows=4, cols=8)
    style_table(t)
    for j,h in enumerate(headers1):
        set_cell_text(t.rows[0].cells[j], h, size=7, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
    for i,n in enumerate(['1.1','1.2','1.3'], start=1):
        set_cell_text(t.rows[i].cells[0], n, size=8, align=WD_ALIGN_PARAGRAPH.CENTER)
        for j in range(1,8):
            set_cell_text(t.rows[i].cells[j], '', size=8)
    set_cell_text(t.rows[1].cells[1], 'Отсутствуют', size=8)
    add_para('2 Обязательные платежи', size=10, bold=True, space_before=6, space_after=2)
    t2 = doc.add_table(rows=4, cols=4)
    style_table(t2)
    for j,h in enumerate(h2):
        set_cell_text(t2.rows[0].cells[j], h, size=8, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
    for i,n in enumerate(['2.1','2.2','2.3'], start=1):
        set_cell_text(t2.rows[i].cells[0], n, size=8, align=WD_ALIGN_PARAGRAPH.CENTER)
        for j in range(1,4):
            set_cell_text(t2.rows[i].cells[j], '', size=8)
    set_cell_text(t2.rows[1].cells[1], 'Отсутствуют', size=8)

add_empty_monetary_section(
    'II. Сведения о кредиторах гражданина',
    '(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,\nкоторые возникли в результате осуществления гражданином\nпредпринимательской деятельности)',
    ''
)
add_para('Сведения о неденежных обязательствах гражданина, которые возникли в результате осуществления гражданином предпринимательской деятельности (в том числе о передаче имущества в собственность, выполнении работ и оказании услуг и так далее):', size=8, space_before=6, space_after=2)
add_para('_'*95, size=9)
add_para('(отсутствуют)', size=9, space_after=8)

# Section III - debtors
add_para('III. Сведения о должниках гражданина', size=10, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=10, space_after=0)
add_para('(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,\nза исключением возникших в результате осуществления гражданином\nпредпринимательской деятельности)', size=9, align=WD_ALIGN_PARAGRAPH.CENTER, space_after=6)
add_para('1 Денежные обязательства', size=10, bold=True, space_after=2)
headers3 = ['№ п/п', 'Содержание\nобязатель-\nства', 'Должник', 'Место\nнахождения\n(место\nжительства)\nдолжника', 'Основание\nвозникно-\nвения', 'Сумма обязательства\nвсего', 'Сумма обязательства\nв том числе\nзадолжен-\nность', 'Штрафы,\nпени\nи иные\nсанкции']
tab3b = doc.add_table(rows=4, cols=8)
style_table(tab3b)
for j,h in enumerate(headers3):
    set_cell_text(tab3b.rows[0].cells[j], h, size=7, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
for i,n in enumerate(['1.1','1.2','1.3'], start=1):
    set_cell_text(tab3b.rows[i].cells[0], n, size=8, align=WD_ALIGN_PARAGRAPH.CENTER)
    for j in range(1,8):
        set_cell_text(tab3b.rows[i].cells[j], '', size=8)
set_cell_text(tab3b.rows[1].cells[1], 'Отсутствуют', size=8)
add_para('2 Обязательные платежи', size=10, bold=True, space_before=6, space_after=2)
tab3c = doc.add_table(rows=4, cols=4)
style_table(tab3c)
h3 = ['№ п/п', 'Наименование налога, сбора или иного\nобязательного платежа', 'Сумма к зачету или возврату\nвсего', 'Сумма к зачету или возврату\nпроценты']
for j,h in enumerate(h3):
    set_cell_text(tab3c.rows[0].cells[j], h, size=8, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
for i,n in enumerate(['2.1','2.2','2.3'], start=1):
    set_cell_text(tab3c.rows[i].cells[0], n, size=8, align=WD_ALIGN_PARAGRAPH.CENTER)
    for j in range(1,4):
        set_cell_text(tab3c.rows[i].cells[j], '', size=8)
set_cell_text(tab3c.rows[1].cells[1], 'Отсутствуют', size=8)
add_para('Сведения о неденежных обязательствах перед гражданином, за исключением возникших в результате осуществления гражданином предпринимательской деятельности (в том числе о передаче имущества в собственность, выполнении работ и оказании услуг и так далее):', size=8, space_before=6, space_after=2)
add_para('_'*95, size=9)
add_para('(отсутствуют)', size=9, space_after=8)

# Section IV
add_para('IV. Сведения о должниках гражданина', size=10, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=10, space_after=0)
add_para('(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,\nкоторые возникли в результате осуществления гражданином\nпредпринимательской деятельности)', size=9, align=WD_ALIGN_PARAGRAPH.CENTER, space_after=6)
add_para('1 Денежные обязательства', size=10, bold=True, space_after=2)
tab4 = doc.add_table(rows=4, cols=8)
style_table(tab4)
for j,h in enumerate(headers3):
    set_cell_text(tab4.rows[0].cells[j], h, size=7, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
for i,n in enumerate(['1.1','1.2','1.3'], start=1):
    set_cell_text(tab4.rows[i].cells[0], n, size=8, align=WD_ALIGN_PARAGRAPH.CENTER)
    for j in range(1,8):
        set_cell_text(tab4.rows[i].cells[j], '', size=8)
set_cell_text(tab4.rows[1].cells[1], 'Отсутствуют', size=8)
add_para('2 Обязательные платежи', size=10, bold=True, space_before=6, space_after=2)
tab4b = doc.add_table(rows=4, cols=4)
style_table(tab4b)
for j,h in enumerate(h3):
    set_cell_text(tab4b.rows[0].cells[j], h, size=8, bold=True, align=WD_ALIGN_PARAGRAPH.CENTER)
for i,n in enumerate(['2.1','2.2','2.3'], start=1):
    set_cell_text(tab4b.rows[i].cells[0], n, size=8, align=WD_ALIGN_PARAGRAPH.CENTER)
    for j in range(1,4):
        set_cell_text(tab4b.rows[i].cells[j], '', size=8)
set_cell_text(tab4b.rows[1].cells[1], 'Отсутствуют', size=8)

add_para('Сведения о неденежных обязательствах перед гражданином, которые возникли в результате осуществления гражданином предпринимательской деятельности (в том числе о передаче имущества в собственность, выполнении работ и оказании услуг и так далее):', size=8, space_before=6, space_after=2)
add_para('_'*95, size=9)
add_para('(отсутствуют)', size=9, space_after=12)

add_para('Достоверность и полноту настоящих сведений подтверждаю.', size=10, space_before=6, space_after=12)
add_para('«____» _____________ 20____ г.          _____________________ / _____________________', size=10)
add_para(' ' * 45 + '(подпись гражданина)        (расшифровка подписи)', size=8)

out = '/home/user/-/bankrotstvo/2_forma_spiska_kreditorov_zapolnennyy.docx'
doc.save(out)
print('saved', out)
