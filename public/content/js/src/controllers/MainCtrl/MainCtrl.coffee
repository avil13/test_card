APP.controller 'MainCtrl', ['$scope', 'swal', 'API', '$filter'
($scope, swal, API, $filter)->
    $scope.t = new Date
    # поиск пользователя
    $scope.search = (data)->
        # send_money.user_id
        $scope.send_money.user_id = if data.originalObject && data.originalObject.id then data.originalObject.id else 0

    # данные пользователя
    $scope.user =
        name: '...'
        group:'-'
        sum: 0
        main_card: 0
        cards: []
        transactions: []


    # данные пользователя
    $scope.getUser = ->
        API.get '/api/user', (data)->
            $scope.user.name = if data && data.name then data.name else '...'
            $scope.user.group = if data && data.group && data.group.title then data.group.title else '-'
            if data.main_card then $scope.user.main_card = data.main_card
    $scope.getUser()


    # транзакции
    $scope.getTransaction = ->
        API.get "/api/transaction/list/#{$filter('date')($scope.t, "yyyy-MM-dd")}", (data)->
            $scope.user.transactions = if data && data.content then data.content else []


    # Получение списка карт или конкретной карты
    $scope.getCard = (card = '', single = false)->
        API.get "/api/card/#{card}", (data)->
            $scope.getTransaction()
            if single
                i = 0
                while i < $scope.user.cards.length
                    if $scope.user.cards[i].id == data.id then $scope.user.cards[i] = data
                    i++
            else
                $scope.user.cards = data
            $scope.user.sum = 0
            for s in $scope.user.cards then $scope.user.sum += s.balance
    $scope.getCard()


    # отправка денег
    $scope.send_money =
        user_id: 0 # получатель
        money: 100 # сумма
        from_card_id: 4 # карта отправителя
    $scope.sendMoney = ->
        API.put "/api/card/#{$scope.send_money.user_id}", $scope.send_money, (data)->
            if data.success then $scope.getCard()
            swal('', data.content, (if data.success then 'success' else 'error'))


    # создание кошелька
    $scope.make_card =
        show: false
        title: ''
    $scope.makeCard = ->
        API.post '/api/card', $scope.make_card, (data)->
            if data.success
                $scope.getCard()
                $scope.make_card.title = ''
                $scope.make_card.show = false
            swal('', data.content, (if data.success then 'success' else 'error'))

    # пополнение кошелька
    $scope.up_card =
        id: 0
        show: false
        money: 0
    $scope.upCard = ->
        API.post '/api/card/up', $scope.up_card, (data)->
            if data.success
                $scope.getCard()
                $scope.up_card.money = 0
                $scope.up_card.show = false
            swal('', data.content, (if data.success then 'success' else 'error'))

]












