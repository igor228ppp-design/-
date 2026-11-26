from reportlab.lib.pagesizes import A4, landscape
from reportlab.lib import colors
from reportlab.lib.units import mm
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.lib.enums import TA_CENTER, TA_RIGHT, TA_LEFT
import os

# Register Cyrillic-capable font
font_paths = [
    '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
    '/usr/share/fonts/truetype/dejavu/DejaVuSansCondensed.ttf',
    '/usr/share/fonts/TTF/DejaVuSans.ttf',
    '/usr/share/fonts/dejavu/DejaVuSans.ttf',
]
bold_paths = [
    '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
    '/usr/share/fonts/truetype/dejavu/DejaVuSansCondensed-Bold.ttf',
    '/usr/share/fonts/TTF/DejaVuSans-Bold.ttf',
    '/usr/share/fonts/dejavu/DejaVuSans-Bold.ttf',
]
base_font = 'Helvetica'
bold_font = 'Helvetica-Bold'
for p in font_paths:
    if os.path.exists(p):
        pdfmetrics.registerFont(TTFont('DejaVu', p))
        base_font = 'DejaVu'
        break
for p in bold_paths:
    if os.path.exists(p):
        pdfmetrics.registerFont(TTFont('DejaVu-Bold', p))
        bold_font = 'DejaVu-Bold'
        break

out = '/home/user/-/bankrotstvo/2_forma_spiska_kreditorov_zapolnennyy.pdf'
doc = SimpleDocTemplate(out, pagesize=landscape(A4), leftMargin=10*mm, rightMargin=10*mm, topMargin=10*mm, bottomMargin=10*mm)
styles = getSampleStyleSheet()
def S(name, parent='Normal', **kw):
    base = styles[parent]
    s = ParagraphStyle(name, parent=base, **kw)
    return s

sNormal = S('N', fontName=base_font, fontSize=7, leading=9, spaceAfter=2)
sSmall = S('Sm', fontName=base_font, fontSize=6, leading=7.5, spaceAfter=1)
sBold = S('B', fontName=bold_font, fontSize=8, leading=10, spaceAfter=2)
sTitle = S('T', fontName=bold_font, fontSize=11, leading=13, alignment=TA_CENTER, spaceAfter=2)
sCenter = S('C', fontName=base_font, fontSize=7, leading=9, alignment=TA_CENTER, spaceAfter=2)
sRight = S('R', fontName=base_font, fontSize=6, leading=7.5, alignment=TA_RIGHT, spaceAfter=2)
sBoldCenter = S('BC', fontName=bold_font, fontSize=8, leading=10, alignment=TA_CENTER, spaceAfter=2)
sSection = S('Sec', fontName=bold_font, fontSize=8, leading=10, alignment=TA_CENTER, spaceBefore=6, spaceAfter=1)
sSub = S('Sub', fontName=base_font, fontSize=6.5, leading=8, alignment=TA_CENTER, spaceAfter=4)

def P(txt, style=sNormal):
    if txt is None: txt = ''
    txt = str(txt).replace('&','&amp;').replace('<','&lt;').replace('>','&gt;').replace('\n','<br/>')
    return Paragraph(txt, style)

def mk_table(data, col_widths, header_rows=1, repeat=True):
    # wrap cell content
    wrapped = []
    for i, row in enumerate(data):
        wrow = []
        for j, cell in enumerate(row):
            if isinstance(cell, Paragraph):
                wrow.append(cell)
            else:
                st = S(f'c{i}_{j}_{id(cell)}', fontName=bold_font if i < header_rows else base_font, fontSize=6 if i < header_rows else 6, leading=7.5 if i < header_rows else 7.5, alignment=TA_CENTER if (i < header_rows or j==0) else TA_LEFT)
                wrow.append(P(cell, st))
        wrapped.append(wrow)
    t = Table(wrapped, colWidths=col_widths, repeatRows=header_rows if repeat else 0)
    t.setStyle(TableStyle([
        ('GRID', (0,0), (-1,-1), 0.5, colors.black),
        ('BACKGROUND', (0,0), (-1,header_rows-1), colors.Color(0.93,0.93,0.93)),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('PADDING', (0,0), (-1,-1), 2),
    ]))
    return t

story = []
story.append(Paragraph('Приложение № 1<br/>к приказу Минэкономразвития России<br/>от 05.08.2015 № 530', sRight))
story.append(Paragraph('ФОРМА', sTitle))
story.append(Paragraph('Список кредиторов и должников гражданина', sTitle))
story.append(Spacer(1, 2*mm))
story.append(Paragraph('Информация о гражданине', sBold))

info = [
    ['фамилия', 'Сурнаев', 'обязательно'],
    ['имя', 'Никита', 'обязательно'],
    ['отчество при наличии', 'Дмитриевич', ''],
    ['в случае изменения фамилии, имени, отчества\nуказать прежние фамилии, имена, отчества', '', 'обязательно'],
    ['дата рождения', '25.08.2004', 'обязательно'],
    ['место рождения', 'г. Ульяновск', 'обязательно'],
    ['СНИЛС', '166-109-704 65', 'обязательно'],
    ['ИНН при наличии', '732717692942', ''],
]
W = doc.width
story.append(mk_table(info, [70*mm, W-100*mm, 30*mm], header_rows=0, repeat=False))
story.append(Spacer(1, 2*mm))
story.append(Paragraph('документ, удостоверяющий личность', sBold))
story.append(mk_table([
    ['вид документа', 'Паспорт гражданина Российской Федерации', 'обязательно'],
    ['серия (при наличии) и номер', '7324 615401', 'обязательно'],
], [70*mm, W-100*mm, 30*mm], header_rows=0, repeat=False))
story.append(Spacer(1, 2*mm))
story.append(Paragraph('адрес регистрации по месту жительства в Российской Федерации *', sBold))
addr = [
    ['субъект Российской Федерации', 'Ульяновская область', 'обязательно'],
    ['район при наличии', '', ''],
    ['город при наличии', 'г. Ульяновск', ''],
    ['населенный пункт (село, поселок и так далее) при наличии', '', ''],
    ['улица (проспект, переулок и так далее) при наличии', 'ул. Отрадная', ''],
    ['номер дома (владения) при наличии', '74', ''],
    ['номер корпуса (строения) при наличии', '', ''],
    ['номер квартиры (офиса) при наличии', '9', ''],
]
story.append(mk_table(addr, [70*mm, W-100*mm, 30*mm], header_rows=0, repeat=False))
story.append(Paragraph('* При отсутствии регистрации по месту жительства в пределах Российской Федерации указать наименование субъекта Российской Федерации по месту пребывания без указания конкретного адреса:', sSmall))

# Section I
story.append(Paragraph('I. Сведения о кредиторах гражданина', sSection))
story.append(Paragraph('(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,<br/>за исключением возникших в результате осуществления гражданином предпринимательской деятельности)', sSub))
story.append(Paragraph('1 Денежные обязательства', sBold))

h1 = ['№ п/п', 'Содержание\nобязательства', 'Кредитор', 'Место нахождения\n(место жительства)\nкредитора', 'Основание\nвозникновения', 'Сумма\nобязательства\nвсего', 'в том числе\nзадолжен-\nность', 'Штрафы, пени\nи иные\nсанкции']
rows1 = [
    ['1.1', 'Кредит (денежное обязательство по договору займа/кредита)', 'АКЦИОНЕРНОЕ ОБЩЕСТВО "АЛЬФА-БАНК"', '107078, г. Москва, ул. Каланчевская, д. 27', 'Договор № F0COM620250519014418 от 19.05.2025; Исп. надпись нотариуса № У-0005719347 от 18.05.2026 (нот. Чекалина Е.В.); ИП № 540858/26/73043-ИП от 20.05.2026 (ОСП № 1 по Засвияжскому р-ну г. Ульяновска)', '100 968,54 руб.\\n(осн. долг 79 696,73;\\nпроценты 19 601,81;\\nрасходы 1 670,00)', '100 968,54 руб.', '—'],
    ['1.2', 'Ущерб в порядке суброгации (денежное обязательство)', 'ПАО "Группа Ренессанс Страхование", ИНН 7725497022', 'г. Москва, вн. тер. г. муниципальный округ Крылатское, ул. Крылатская, д. 17, корп. 1', 'Решение Засвияжского районного суда г. Ульяновска по делу № 2-1759/2026, исп. лист № 73RS0002#2-1759/2026#1 от 07.07.2026; ИП № 5673420/26/73043-ИП от 08.07.2026 (ОСП № 1 по Засвияжскому р-ну г. Ульяновска)', '276 709,87 руб.\\n(ущерб 267 679,87;\\nгоспошлина 9 030,00);\\nпроценты по ст. 395 ГК РФ — сумма на дату составления подлежит уточнению', '276 709,87 руб.\\n(без % по ст. 395 ГК РФ)', '—'],
    ['1.3', 'Задолженность по кредитным платежам', 'АО "ТБанк"', '127287, Россия, г. Москва, ул. Хуторская 2-я, д. 38А, стр. 26', 'Судебный приказ № 2-1142/2026 от 03.07.2026, Судебный участок № 6 Засвияжского района г. Ульяновска; ИП № 5729875/26/73043-ИП от 09.09.2026 (ОСП № 1 по Засвияжскому р-ну г. Ульяновска)', '61 026,53 руб.', '61 026,53 руб.', '—'],
    ['1.4', 'Задолженность по кредитной карте (Кредитная СберКарта ****2137)', 'ПАО Сбербанк', '117312, г. Москва, ул. Вавилова, д. 19', 'Договор № 99TKPR25020602549676 от 06.02.2025; справка о задолженности от 24.11.2026 (СберБанк Онлайн)', '83 098,12 руб.\\n(срочная 42 856,50;\\nпросроч./штрафы/пени/неустойки 40 241,62 — разбивка не предоставлена)', '83 098,12 руб.', '40 241,62 руб.\\n(разбивка не предоставлена)'],
    ['1.5', 'Задолженность по кредитному договору (Супер Сплит / Улучшенный Сплит)', 'АО "Яндекс Банк" (лиц. Банка России № 3027)', '115035, г. Москва, ул. Садовническая, д. 82, стр. 2', 'Кредитный договор № КС20250121768347299190 от 21.01.2025; справка о задолженности исх. № 202611249899214 от 24.11.2026', '64 760,98 руб.\\n(просроч. осн. долг 39 913,67;\\nпросроч. проценты 9 107,01;\\nнеустойка 15 740,30)', '64 760,98 руб.', '15 740,30 руб.\\n(неустойка)'],
]
cw1 = [10*mm, 28*mm, 35*mm, 35*mm, 60*mm, 30*mm, 28*mm, 28*mm]
# scale to width
total = sum(cw1)
cw1 = [w * W / total for w in cw1]
story.append(mk_table([h1]+rows1, cw1, header_rows=1))

story.append(Spacer(1, 2*mm))
story.append(Paragraph('2 Обязательные платежи', sBold))
h2 = ['№ п/п', 'Наименование налога, сбора или иного обязательного платежа', 'Недоимка', 'Штрафы, пени и иные санкции']
rows2 = [
    ['2.1','Налог на имущество физических лиц\n(уведомление от 02.09.2026 №323168554, налоговый орган 7300, срок до 01.12.2026)','322,00 руб.','—'],
    ['2.2','Транспортный налог (2024)\n(иные документы от 25.04.2025 №2024ГД01)','166,09 руб.','98,96 руб. (пени по строкам 2.2–2.3 совокупно по данным ФНС; разбивка не предоставлена)'],
    ['2.3','Налог на имущество физических лиц (2024)\n(иные документы от 07.05.2025 №2024ГД01)','86,91 руб.','0,00 руб. (пени учтены в строке 2.2)'],
    ['2.4','Исполнительский сбор\n(ИП № 540858/26/73043-ИП от 20.05.2026, ОСП № 1 по Засвияжскому р-ну г. Ульяновска УФССП по Ульяновской обл.)','12 116,22 руб.','—'],
    ['2.5','Исполнительский сбор\n(ИП № 5673420/26/73043-ИП от 08.07.2026, ОСП № 1 по Засвияжскому р-ну г. Ульяновска УФССП по Ульяновской обл.)','33 205,18 руб.','—'],
    ['2.6','Исполнительский сбор\n(ИП № 5729875/26/73043-ИП от 09.09.2026, ОСП № 1 по Засвияжскому р-ну г. Ульяновска УФССП по Ульяновской обл.)','7 317,90 руб.','—'],
]
cw2 = [15*mm, W-75*mm, 30*mm, 30*mm]
story.append(mk_table([h2]+rows2, cw2, header_rows=1))
story.append(Paragraph('Сведения о неденежных обязательствах гражданина, за исключением возникших в результате осуществления гражданином предпринимательской деятельности: (отсутствуют)', sSmall))

def empty_section(title, subtitle, debtors=False):
    story.append(Paragraph(title, sSection))
    story.append(Paragraph(subtitle, sSub))
    story.append(Paragraph('1 Денежные обязательства', sBold))
    hh = ['№ п/п', 'Содержание обязательства', 'Должник' if debtors else 'Кредитор', 'Место нахождения (место жительства)', 'Основание возникновения', 'Сумма обязательства всего', 'в том числе задолженность', 'Штрафы, пени и иные санкции']
    rr = [['1.1','Отсутствуют','','','','','',''],['1.2','','','','','','',''],['1.3','','','','','','','']]
    story.append(mk_table([hh]+rr, cw1, header_rows=1))
    story.append(Spacer(1, 2*mm))
    story.append(Paragraph('2 Обязательные платежи', sBold))
    if debtors:
        hh2 = ['№ п/п', 'Наименование налога, сбора или иного обязательного платежа', 'Сумма к зачету или возврату всего', 'Сумма к зачету или возврату проценты']
    else:
        hh2 = h2
    story.append(mk_table([hh2]+[['2.1','Отсутствуют','',''],['2.2','','',''],['2.3','','','']], cw2, header_rows=1))

empty_section('II. Сведения о кредиторах гражданина', '(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,<br/>которые возникли в результате осуществления гражданином предпринимательской деятельности)')
story.append(Paragraph('Сведения о неденежных обязательствах гражданина, которые возникли в результате осуществления гражданином предпринимательской деятельности: (отсутствуют)', sSmall))

empty_section('III. Сведения о должниках гражданина', '(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,<br/>за исключением возникших в результате осуществления гражданином предпринимательской деятельности)', debtors=True)
story.append(Paragraph('Сведения о неденежных обязательствах перед гражданином, за исключением возникших в результате осуществления гражданином предпринимательской деятельности: (отсутствуют)', sSmall))

empty_section('IV. Сведения о должниках гражданина', '(по денежным обязательствам и (или) обязанности по уплате обязательных платежей,<br/>которые возникли в результате осуществления гражданином предпринимательской деятельности)', debtors=True)
story.append(Paragraph('Сведения о неденежных обязательствах перед гражданином, которые возникли в результате осуществления гражданином предпринимательской деятельности: (отсутствуют)', sSmall))

story.append(Spacer(1, 4*mm))
story.append(Paragraph('Достоверность и полноту настоящих сведений подтверждаю.', sNormal))
story.append(Spacer(1, 4*mm))
story.append(Paragraph('«____» _____________ 20____ г. &nbsp;&nbsp;&nbsp;&nbsp; _____________________ / _____________________', sNormal))
story.append(Paragraph('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(подпись гражданина) &nbsp;&nbsp;&nbsp;&nbsp; (расшифровка подписи)', sSmall))

doc.build(story)
print('saved', out)
