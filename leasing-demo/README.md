<h1 align="center">АИС для обработки заявок</h1>

### Контактная информация
Меня зовут Анна, я студент 3 курса на направлении "программная инженерия".
Моя почта: anna.fittsdzherald@mail.ru
Версия проекта v1.0
Дата создания: 16.06.2022

### Общая информация
Предметная область: лизинговый центр.
Суть проекта - создать сервис для обработки заявок лизинговой компании.
Сервер принимает на вход документ csv, парсит строки и готовить список заявок по клиентам.
Авторизированному пользователю можно их посмотреть и удалить.

### Библиотеки
- в основе MVC фреймворк Laravel
- MediaFilter - для облегчения работы с загрузкой файлов
- SanCtum - для облегчения работы с авторизацией

### Сниппет:
```php
$originalFile = $request->file('file');
        $csv = Csv::create([
            'tag' => $request['tag'],
            'filename' => $originalFile->getClientOriginalName(),
            'user_id' => auth()->id(),
        ]);
        $file = $csv->addMediaFromRequest('file')
            ->toMediaCollection('imports');
        $filename = storage_path('app\\public\\'.$file->id.'\\'.$file->file_name);
        $skip = TRUE;
        $row = 0;
```

### Требования
Для пользования сервером дополнительных инструментов не требуется, но БД хранится локально на компьютере

- **Функциональные требования**
  БД должна хранить:
    - список клиентов
    - список контактов (почты и телефонов)
    - список адресов
    - список типов объектов на лизинг
    - срок лизинга
    - список компаний
    - список заявок

Сервер должен предоставить доступ к:
- просмотру клиентов
- просмотру заявок по клиенту
- удалению заявок
- загрузке csv-документа
- составлению json-файла
- интеграции с CRM-системой (!)
- просмотру списку типов объектов на лизинг
- не допускать неавторизированного пользователя к просмотру данных

- **Системные требования**
    - интеграция с CRM-системой (!)
    - необходим лог всех запросов (!)
    - бэкап БД раз в месяц (!)

- **Нефункциональные требования**
    - взаимодействие с БД
    - парсинг csv-документа
    - поддержка объемных csv-документов
    - система авторизации

> ! - помечено то, что на данный момент не представаляется возможным реализовать

### Архитектура
Код исполнен в соответствии с loose coupling (слабая связанность)
& high cohesion (высокая сплочённость)

HLD-документации по нотации C4
HLD (high-level design) – верхнеуровневое описание архитектуры системы,
где представлены основные компоненты и их взаимодействия
согласно нотации Context+Container+Component+Code=С4.
![alt text](https://github.com/Anna228322/PHPSemesterWork/blob/master/readme_img_1.jpg?raw=true)

### Код на примере 4-х взаимосвязанных классов:
```php
 $file = $csv->addMediaFromRequest('file')
            ->toMediaCollection('imports');
        $filename = storage_path('app\\public\\'.$file->id.'\\'.$file->file_name);
        $row = 1;
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $row++;
                $company = Company::create([
                    'name' => $data[7],
                    'email' => $data[9],
                    'phone' => $data[8],
                    'address' => $data[10]
                ]);
                $contact = Contact::create([
                    'name' => $data[3],
                    'email' => $data[5],
                    'phone' => $data[4],
                    'address' => $data[6],
                    'company_id' => $company->id
                ]);
                Application::create([
                    'contact_id' => $contact->id,
                    'csv_id' => $csv->id,
                    'sum' => $data[2],
                    'object_type' => $data[0],
                    'lease_term' => $data[1],
                ]);
            }
            fclose($handle);
        }
```

### Диаграмма последовательности (sequence diagram)

![alt text](https://github.com/Anna228322/PHPSemesterWork/blob/master/readme_img_0.jpg?raw=true)
