umożliwienie kontroli flow aplikacji, np flow checkoutu

rózne możliwości definicji flow yml/xml/adnotacje. adnotacje - 1. wersja

np

class ExampleController
{

	/*
	* @Flow(name='checkout', step='terms_of_service')
	* @Flow(name='another_flow', step='step_can_be_shared_among_flows')
	*/	
	public function exampleAction()
	{
		//..
	}


}


wprowadzić StepRepository i Step jako obiekt


case gdy flow (* - opcjonalny)

Q: czy można cofać się do kroków wcześniejszych
          +
A -> B -> C -> D* -> E - jest w C, czyli był w A i B. Czy teraz może otworzyć stronę A ?

A: otwarcie strony to nie step. można stworzyć 2 akcję. 1. renderuje (get), 2. wysyła form (post)

i adnotację @Flow używać tylko dla 2. akcji.


Q: co robi @Flow przed akcją ?
A: sprawdza czy krok jest możliwy do wykonania

Q: co robi @Flow po akcji
A: jeżeli akcja zwróciła 200 to wykonuje przesunięcie




zastanowić się czy da się i jest sens pracować tylko na obiektach step/flow a nie na ich nazwach

commit 71a9cd6d43a55350ba187f77219deddbc4247eec - pełny FlowControl component - na 99%

bundle

TraverserProvider - np zwraca usera z sesji o ile implementuje interfejs

Q: dlaczego nie taka konfiguracja

enabled_traverser_providers:
    - session_traverser_provider

A: 1) żeby dodać nowego traversera trzeba nadpisać konfigurację bundla, bo enabled_traverser_providers miałby
ładować definicje traverserów znanych bundlowi, więc siłą rzeczy tych TYLKO wewn bundla

więc - Compiler Passy


traverser_providers:
    session:
        service: session_traverser_provider
        flows: [fa, fb, fc]

w tym konfigu provider nie wiem które flow jest podmiotem - wie to klasa która providera uruchamia - like it, bo
to nie powinno go obchodzic




---------------------------

ConfigurationReader - czyta konfigurację dla danej akcji kontrolera, czyli jakim stepem jest dana akcja
- annoation - bierze konfigurację z annotacji akcji
- xml - bierze konfigurację z pliku xml
- yml - bierze konfigurację z pliku yml



skomplikowany flow


complex_flow:
    - step1
    - [step2.1,                ~,       step2.3]
    - [step3.1,                ~,       step3.3]
    - [[step4.1.1, step4.1.2], ~,       step4.3]
    - [step5.1,                step5.2, ~      ]
    - step6

    tzn

    1
    |               \           \
    2.1             |           2.3
    |               |           |
    3.1             |           3.3
    |      \        |           |
    4.1.1   4.1.2   |           4.3
    |      /        |           |
    5.1             5.2         |
    |              /         /
    |            /       /
    |          /     /
           6

mapowanie na kilka prostych flow:

f1:    1 -> 2.1 -> 3.1 -> 4.1.1 -> 5.1 -> 6
f2:    1 -> 2.1 -> 3.1 -> 4.1.2 -> 5.1 -> 6
f3:    1 -> 5.2 -> 6
f4:    1 -> 2.3 -> 3.3 -> 4.3 -> 6

każde przejście zadziała po takim zmapowaniu i nie trzeba obsługiwać skomplikowanych flow "kompleksowo"