<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="section-title text-center mb-5">Кредитование на покупку автомобиля</h1>
            
            <div class="card border-0 shadow mb-4">
                <div class="card-body p-5">
                    <h3 class="mb-4">Условия кредитования</h3>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Параметр</th>
                                    <th>Значение</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ставка</td>
                                    <td><strong>от 3.5%</strong> годовых</td>
                                </tr>
                                <tr>
                                    <td>Первоначальный взнос</td>
                                    <td><strong>от 10%</strong></td>
                                </tr>
                                <tr>
                                    <td>Срок кредита</td>
                                    <td><strong>до 7 лет</strong> (84 месяца)</td>
                                </tr>
                                <tr>
                                    <td>Максимальная сумма</td>
                                    <td><strong>до 15 млн ₽</strong></td>
                                </tr>
                                <tr>
                                    <td>Решение</td>
                                    <td><strong>от 30 минут</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-center mb-3">
                            <div class="p-3">
                                <i class="fas fa-building fa-3x text-gold mb-3"></i>
                                <h6>Сбербанк</h6>
                                <p class="small text-muted">от 4.5%</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="p-3">
                                <i class="fas fa-building fa-3x text-gold mb-3"></i>
                                <h6>ВТБ</h6>
                                <p class="small text-muted">от 4.2%</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="p-3">
                                <i class="fas fa-building fa-3x text-gold mb-3"></i>
                                <h6>Альфа-Банк</h6>
                                <p class="small text-muted">от 3.9%</p>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Заявка на кредит</h4>
                    <form action="/services/submitCredit" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="name" placeholder="ФИО" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="tel" class="form-control" name="phone" placeholder="Телефон" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="number" class="form-control" name="income" placeholder="Ежемесячный доход" required>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="text" class="form-control" name="car" placeholder="Желаемый автомобиль" required>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="initial_payment">
                                    <label class="form-check-label" for="initial_payment">
                                        Есть первоначальный взнос
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold w-100">Отправить заявку</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>